"""
API Token Manager for Python

Usage:
    manager = ApiTokenManager()
    response = manager.call('GET', '/applications')
    
Or with context manager:
    with ApiTokenManager() as manager:
        apps = manager.call('GET', '/applications')
        print(apps)
"""

import requests
import json
import os
import time
from typing import Optional, Dict, Any


class ApiTokenManager:
    """Manages API token lifecycle with automatic refresh on expiry"""
    
    def __init__(
        self,
        api_url: str = "http://127.0.0.1:8000/api",
        email: str = "olomidereck@hotmail.com",
        password: str = "123456",
        token_file: str = ".api_token.json",
        token_expiry: int = 60 * 60 * 24  # 24 hours
    ):
        self.api_url = api_url
        self.email = email
        self.password = password
        self.token_file = token_file
        self.token_expiry = token_expiry
        self.token: Optional[str] = None
        self.token_timestamp: Optional[float] = None
        
        self._load_token()
    
    def _load_token(self) -> None:
        """Load token from file if it exists"""
        try:
            if os.path.exists(self.token_file):
                with open(self.token_file, 'r') as f:
                    data = json.load(f)
                    self.token = data.get('token')
                    self.token_timestamp = data.get('timestamp')
        except Exception as e:
            print(f"Failed to load token: {e}")
    
    def _save_token(self) -> None:
        """Save token to file"""
        try:
            with open(self.token_file, 'w') as f:
                json.dump({
                    'token': self.token,
                    'timestamp': self.token_timestamp
                }, f, indent=2)
        except Exception as e:
            print(f"Failed to save token: {e}")
    
    def _is_token_expired(self) -> bool:
        """Check if current token is expired"""
        if not self.token or not self.token_timestamp:
            return True
        
        age = time.time() - self.token_timestamp
        return age > self.token_expiry
    
    def refresh_token(self) -> str:
        """Refresh the API token"""
        print("🔄 Refreshing API token...")
        
        try:
            response = requests.post(
                f"{self.api_url}/login",
                data={
                    'email': self.email,
                    'password': self.password
                },
                timeout=10
            )
            response.raise_for_status()
            
            data = response.json()
            if 'token' in data:
                self.token = data['token']
                self.token_timestamp = time.time()
                self._save_token()
                print("✅ Token refreshed successfully (expires in 24 hours)")
                return self.token
            else:
                raise Exception(f"No token in response: {data}")
                
        except requests.exceptions.RequestException as e:
            print(f"❌ Failed to refresh token: {e}")
            raise
    
    def get_token(self) -> str:
        """Get current token, refresh if needed"""
        if self._is_token_expired():
            return self.refresh_token()
        else:
            hours_left = (self.token_expiry - (time.time() - self.token_timestamp)) / 3600
            print(f"✓ Using cached token (expires in {int(hours_left)} hours)")
            return self.token
    
    def call(
        self,
        method: str,
        endpoint: str,
        data: Optional[Dict[str, Any]] = None,
        **kwargs
    ) -> Dict[str, Any]:
        """Make API call with automatic token management"""
        token = self.get_token()
        
        headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }
        headers.update(kwargs.get('headers', {}))
        
        url = f"{self.api_url}{endpoint}"
        
        try:
            if method.upper() == 'GET':
                response = requests.get(url, headers=headers, timeout=10, **kwargs)
            elif method.upper() == 'POST':
                response = requests.post(url, json=data, headers=headers, timeout=10, **kwargs)
            elif method.upper() == 'PUT':
                response = requests.put(url, json=data, headers=headers, timeout=10, **kwargs)
            elif method.upper() == 'PATCH':
                response = requests.patch(url, json=data, headers=headers, timeout=10, **kwargs)
            elif method.upper() == 'DELETE':
                response = requests.delete(url, headers=headers, timeout=10, **kwargs)
            else:
                raise ValueError(f"Unsupported HTTP method: {method}")
            
            response.raise_for_status()
            
            try:
                return response.json()
            except:
                return {'status': response.status_code, 'text': response.text}
                
        except requests.exceptions.RequestException as e:
            print(f"API Error: {e}")
            raise
    
    def clear_token(self) -> None:
        """Clear stored token"""
        try:
            if os.path.exists(self.token_file):
                os.remove(self.token_file)
            self.token = None
            self.token_timestamp = None
            print("✓ Token cleared")
        except Exception as e:
            print(f"Failed to clear token: {e}")
    
    def __enter__(self):
        """Context manager entry"""
        return self
    
    def __exit__(self, exc_type, exc_val, exc_tb):
        """Context manager exit"""
        pass


# Example usage
if __name__ == '__main__':
    # Simple usage
    manager = ApiTokenManager()
    
    try:
        # List applications
        print("\n📋 Listing applications...")
        apps = manager.call('GET', '/applications')
        print(json.dumps(apps, indent=2))
        
        # Get application age
        if apps and isinstance(apps, list) and len(apps) > 0:
            app_id = apps[0]['id']
            print(f"\n⏰ Getting age of application {app_id}...")
            age = manager.call('GET', f'/applications/{app_id}/age')
            print(json.dumps(age, indent=2))
    
    except Exception as e:
        print(f"Error: {e}")
    
    # Context manager usage
    print("\n" + "="*50)
    print("Using context manager:")
    print("="*50)
    
    try:
        with ApiTokenManager() as manager:
            print("\n📋 Listing applications (using context manager)...")
            apps = manager.call('GET', '/applications')
            print(f"Found {len(apps) if isinstance(apps, list) else 0} applications")
    
    except Exception as e:
        print(f"Error: {e}")
