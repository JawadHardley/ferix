# API Token Manager Scripts

Automatic API token lifecycle management for the Ferix API. Choose the script that fits your workflow:

## 🔧 Available Scripts

### 1. **Postman Pre-request Script** (`postman-token-manager.js`)
Best for: Interactive API testing in Postman

**Setup:**
1. In Postman, go to your Collection → Pre-request Scripts
2. Copy the entire content from `postman-token-manager.js`
3. Paste it into the Pre-request Scripts tab

**Features:**
- Automatically logs in and gets a new token when needed
- Stores token in Postman environment variables
- Shows token expiry time remaining
- No additional configuration needed for simple cases

**Environment Variables (Optional):**
```
api_url: http://127.0.0.1:8000/api
email: olomidereck@hotmail.com
password: 123456
```

---

### 2. **Bash Script** (`token-manager.sh`)
Best for: curl commands and shell scripts

**Setup:**
```bash
chmod +x token-manager.sh
source token-manager.sh
```

**Usage:**
```bash
# List applications
api_call GET /applications

# Create application
api_call POST /applications '{"feri_type":"regional","transporter_company":"Test Company"}'

# Get application age
api_call GET /applications/1/age

# Clear token
clear_token
```

**Features:**
- Automatic token refresh on expiry
- Caches token in `.api_token` file
- Shows token remaining time
- Works with all curl commands

---

### 3. **Node.js Module** (`token-manager.js`)
Best for: Node.js applications and automated testing

**Installation:**
```bash
npm install
```

**Usage:**
```javascript
const ApiTokenManager = require('./token-manager.js');
const manager = new ApiTokenManager();

(async () => {
    // List applications
    const apps = await manager.call('GET', '/applications');
    console.log(apps);
    
    // Create application
    const newApp = await manager.call('POST', '/applications', {
        feri_type: 'regional',
        transporter_company: 'Test Company'
    });
    console.log(newApp);
})();
```

**Features:**
- Promise-based API
- Automatic token management
- Persistent token storage
- Error handling

---

### 4. **Python Module** (`token_manager.py`)
Best for: Python scripts and automation

**Installation:**
```bash
pip install requests
```

**Usage:**
```python
from token_manager import ApiTokenManager

# Simple usage
manager = ApiTokenManager()
apps = manager.call('GET', '/applications')
print(apps)

# Or with context manager
with ApiTokenManager() as manager:
    apps = manager.call('GET', '/applications')
    age = manager.call('GET', '/applications/1/age')
```

**Features:**
- Clean, Pythonic API
- Context manager support
- Automatic token refresh
- File-based persistence

**Run example:**
```bash
python token_manager.py
```

---

## 🔑 Token Details

- **Duration**: 24 hours
- **Storage**: Automatic in respective file formats
- **Expiry Check**: Automatic before each request
- **Refresh**: Automatic when expired

---

## 📊 Default Credentials

All scripts use these defaults (configurable):
- **Email**: olomidereck@hotmail.com
- **Password**: 123456
- **API URL**: http://127.0.0.1:8000/api

---

## 🛠️ Configuration

### Postman
Set environment variables in Postman:
- `api_url`
- `email`
- `password`

### Bash
Edit the variables at the top of `token-manager.sh`

### Node.js
```javascript
const manager = new ApiTokenManager({
    apiUrl: 'http://127.0.0.1:8000/api',
    email: 'your-email@example.com',
    password: 'your-password',
    tokenFile: '.api_token.json',
    tokenExpiry: 60 * 60 * 24 // 24 hours
});
```

### Python
```python
manager = ApiTokenManager(
    api_url='http://127.0.0.1:8000/api',
    email='your-email@example.com',
    password='your-password',
    token_file='.api_token.json',
    token_expiry=60 * 60 * 24  # 24 hours
)
```

---

## 🚀 Quick Start

**For Postman testing:**
1. Copy `postman-token-manager.js` content to Collection Pre-request Scripts
2. Test any endpoint - token auto-refreshes

**For curl/bash:**
```bash
source token-manager.sh
api_call GET /applications
```

**For Node.js:**
```bash
node -e "const M = require('./token-manager.js'); new M().call('GET', '/applications').then(console.log)"
```

**For Python:**
```bash
python token_manager.py
```

---

## 📝 API Endpoints

- `POST /api/login` - Get authentication token
- `GET /api/applications` - List applications
- `POST /api/applications` - Create application
- `GET /api/applications/{id}` - Get application details
- `GET /api/applications/{id}/age` - Get application age
- `GET /api/applications/{id}/certificate` - Download certificate
- `GET /api/applications/{id}/invoice` - Download invoice

---

## 🐛 Troubleshooting

**Token not refreshing:**
- Clear token file (`.api_token` or `.api_token.json`)
- Verify credentials are correct
- Check API server is running on http://127.0.0.1:8000

**"Authorization failed" errors:**
- Check token expiry: `echo $api_token` (bash)
- Verify email/password combination
- Clear and re-authenticate

**File permission errors:**
- Make bash script executable: `chmod +x token-manager.sh`
- Ensure write permission in current directory

---

## 📄 License

Part of Ferix API project
