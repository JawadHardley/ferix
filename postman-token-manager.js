/**
 * Postman Pre-request Script for Automatic Token Management
 * Add this to Postman > Collection > Pre-request Scripts
 * 
 * This script will:
 * 1. Check if API token exists in environment variables
 * 2. If not, or if expired, automatically login to get a new token
 * 3. Set the Authorization header for all requests
 */

// Configuration
const apiUrl = pm.environment.get("api_url") || "http://127.0.0.1:8000/api";
const email = pm.environment.get("email") || "olomidereck@hotmail.com";
const password = pm.environment.get("password") || "123456";
const tokenKey = "api_token";
const tokenTimestampKey = "api_token_timestamp";
const tokenExpiry = 60 * 60 * 24; // 24 hours in seconds

// Get current token and timestamp
let token = pm.environment.get(tokenKey);
let tokenTimestamp = pm.environment.get(tokenTimestampKey);
let currentTime = Math.floor(Date.now() / 1000);

// Check if token is expired or doesn't exist
let isTokenExpired = !token || !tokenTimestamp || (currentTime - parseInt(tokenTimestamp)) > tokenExpiry;

if (isTokenExpired) {
    console.log("Token expired or not found. Fetching new token...");
    
    // Create login request
    const loginRequest = {
        url: `${apiUrl}/login`,
        method: "POST",
        header: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: {
            mode: "urlencoded",
            urlencoded: [
                { key: "email", value: email },
                { key: "password", value: password }
            ]
        }
    };

    // Send login request
    pm.sendRequest(loginRequest, function(err, response) {
        if (err) {
            console.error("Failed to get token:", err);
            pm.test("Token Refresh", function() {
                pm.expect(err).to.be.null;
            });
        } else {
            try {
                const jsonResponse = response.json();
                
                if (jsonResponse.token) {
                    // Save token and timestamp to environment
                    pm.environment.set(tokenKey, jsonResponse.token);
                    pm.environment.set(tokenTimestampKey, currentTime.toString());
                    
                    console.log("✓ New token obtained successfully");
                    console.log("Token expires in 24 hours");
                } else {
                    console.error("No token in response:", jsonResponse);
                }
            } catch (e) {
                console.error("Failed to parse response:", e);
            }
        }
    });
} else {
    const hoursLeft = Math.floor((tokenExpiry - (currentTime - parseInt(tokenTimestamp))) / 3600);
    console.log(`✓ Using existing token (expires in ${hoursLeft} hours)`);
}

// Set Authorization header for this request
pm.request.headers.add({
    key: "Authorization",
    value: "Bearer " + pm.environment.get(tokenKey)
});
