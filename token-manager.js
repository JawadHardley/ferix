/**
 * API Token Manager for Node.js/JavaScript
 * 
 * Usage:
 * const tokenManager = new ApiTokenManager();
 * const response = await tokenManager.call('POST', '/applications', data);
 */

const fs = require('fs');
const path = require('path');
const http = require('http');
const querystring = require('querystring');

class ApiTokenManager {
    constructor(config = {}) {
        this.apiUrl = config.apiUrl || "http://127.0.0.1:8000/api";
        this.email = config.email || "olomidereck@hotmail.com";
        this.password = config.password || "123456";
        this.tokenFile = config.tokenFile || ".api_token.json";
        this.tokenExpiry = config.tokenExpiry || (60 * 60 * 24); // 24 hours in seconds
        this.token = null;
        this.tokenTimestamp = null;
        
        this.loadToken();
    }

    /**
     * Load token from file if it exists
     */
    loadToken() {
        try {
            if (fs.existsSync(this.tokenFile)) {
                const data = JSON.parse(fs.readFileSync(this.tokenFile, 'utf8'));
                this.token = data.token;
                this.tokenTimestamp = data.timestamp;
            }
        } catch (err) {
            console.error("Failed to load token:", err.message);
        }
    }

    /**
     * Save token to file
     */
    saveToken() {
        try {
            fs.writeFileSync(this.tokenFile, JSON.stringify({
                token: this.token,
                timestamp: this.tokenTimestamp
            }, null, 2));
        } catch (err) {
            console.error("Failed to save token:", err.message);
        }
    }

    /**
     * Check if token is expired
     */
    isTokenExpired() {
        if (!this.token || !this.tokenTimestamp) {
            return true;
        }
        const currentTime = Math.floor(Date.now() / 1000);
        const age = currentTime - this.tokenTimestamp;
        return age > this.tokenExpiry;
    }

    /**
     * Refresh the API token
     */
    async refreshToken() {
        return new Promise((resolve, reject) => {
            console.log("🔄 Refreshing API token...");

            const postData = querystring.stringify({
                email: this.email,
                password: this.password
            });

            const options = {
                hostname: new URL(this.apiUrl).hostname,
                port: new URL(this.apiUrl).port || 80,
                path: '/api/login',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Content-Length': Buffer.byteLength(postData)
                }
            };

            const req = http.request(options, (res) => {
                let data = '';
                res.on('data', (chunk) => data += chunk);
                res.on('end', () => {
                    try {
                        const response = JSON.parse(data);
                        if (response.token) {
                            this.token = response.token;
                            this.tokenTimestamp = Math.floor(Date.now() / 1000);
                            this.saveToken();
                            console.log("✅ Token refreshed successfully");
                            resolve(this.token);
                        } else {
                            reject(new Error("No token in response: " + data));
                        }
                    } catch (err) {
                        reject(err);
                    }
                });
            });

            req.on('error', reject);
            req.write(postData);
            req.end();
        });
    }

    /**
     * Get current token (refresh if needed)
     */
    async getToken() {
        if (this.isTokenExpired()) {
            await this.refreshToken();
        } else {
            const hoursLeft = Math.floor((this.tokenExpiry - (Math.floor(Date.now() / 1000) - this.tokenTimestamp)) / 3600);
            console.log(`✓ Using cached token (expires in ${hoursLeft} hours)`);
        }
        return this.token;
    }

    /**
     * Make API call
     */
    async call(method, endpoint, data = null) {
        const token = await this.getToken();
        
        return new Promise((resolve, reject) => {
            const url = new URL(this.apiUrl + endpoint);
            const options = {
                hostname: url.hostname,
                port: url.port || 80,
                path: url.pathname + url.search,
                method: method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            };

            const req = http.request(options, (res) => {
                let responseData = '';
                res.on('data', (chunk) => responseData += chunk);
                res.on('end', () => {
                    try {
                        resolve(JSON.parse(responseData));
                    } catch (err) {
                        resolve(responseData);
                    }
                });
            });

            req.on('error', reject);
            
            if (data) {
                req.write(JSON.stringify(data));
            }
            
            req.end();
        });
    }

    /**
     * Clear stored token
     */
    clearToken() {
        try {
            if (fs.existsSync(this.tokenFile)) {
                fs.unlinkSync(this.tokenFile);
            }
            this.token = null;
            this.tokenTimestamp = null;
            console.log("✓ Token cleared");
        } catch (err) {
            console.error("Failed to clear token:", err.message);
        }
    }
}

module.exports = ApiTokenManager;

// Example usage:
/*
const tokenManager = new ApiTokenManager();

(async () => {
    try {
        // Get applications
        const apps = await tokenManager.call('GET', '/applications');
        console.log("Applications:", apps);
        
        // Create application
        const newApp = await tokenManager.call('POST', '/applications', {
            feri_type: 'regional',
            transporter_company: 'Test Company',
            // ... other fields
        });
        console.log("Created:", newApp);
        
    } catch (err) {
        console.error("Error:", err.message);
    }
})();
*/
