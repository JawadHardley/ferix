#!/bin/bash

##############################################################################
# API Token Manager Script for Ferix
# 
# Usage: source ./token-manager.sh
# Then use: api_call GET /applications
#         api_call POST /applications '{"key":"value"}'
#
##############################################################################

API_URL="http://127.0.0.1:8000/api"
EMAIL="olomidereck@hotmail.com"
PASSWORD="123456"
TOKEN_FILE=".api_token"
TOKEN_EXPIRY=$((60 * 60 * 24))  # 24 hours in seconds

# Function to get current timestamp
get_timestamp() {
    date +%s
}

# Function to refresh token
refresh_token() {
    echo "🔄 Refreshing API token..."
    
    response=$(curl -s -X POST "$API_URL/login" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -d "email=$EMAIL&password=$PASSWORD")
    
    token=$(echo "$response" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    
    if [ -z "$token" ]; then
        echo "❌ Failed to get token. Response: $response"
        return 1
    fi
    
    # Save token and timestamp
    echo "$token" > "$TOKEN_FILE"
    echo "$(get_timestamp)" >> "$TOKEN_FILE"
    
    echo "✅ Token refreshed successfully (expires in 24 hours)"
    echo "$token"
}

# Function to get current token
get_token() {
    if [ ! -f "$TOKEN_FILE" ]; then
        refresh_token
        return
    fi
    
    token=$(head -n 1 "$TOKEN_FILE")
    timestamp=$(tail -n 1 "$TOKEN_FILE")
    current_time=$(get_timestamp)
    
    # Check if token is expired
    age=$((current_time - timestamp))
    if [ $age -gt $TOKEN_EXPIRY ]; then
        echo "⏰ Token expired (age: $((age / 3600)) hours). Refreshing..."
        refresh_token
    else
        hours_left=$(((TOKEN_EXPIRY - age) / 3600))
        echo "✓ Using cached token (expires in $hours_left hours)" >&2
        echo "$token"
    fi
}

# Function to make API calls
api_call() {
    local method=$1
    local endpoint=$2
    local data=$3
    
    token=$(get_token)
    
    if [ "$method" = "GET" ]; then
        curl -X "$method" \
            -H "Authorization: Bearer $token" \
            -H "Content-Type: application/json" \
            "$API_URL$endpoint"
    else
        curl -X "$method" \
            -H "Authorization: Bearer $token" \
            -H "Content-Type: application/json" \
            -d "$data" \
            "$API_URL$endpoint"
    fi
}

# Function to clear stored token
clear_token() {
    rm -f "$TOKEN_FILE"
    echo "✓ Token cleared"
}

echo "📦 API Token Manager loaded"
echo "   Use: api_call METHOD ENDPOINT [DATA]"
echo "   Example: api_call GET /applications"
echo "   Example: api_call POST /applications '{\"key\":\"value\"}'"
