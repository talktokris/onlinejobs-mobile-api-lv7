# ✅ Verify Docker File Sharing

## Step-by-Step Instructions

### 1. Open Docker Desktop Settings
- Click the Docker icon in your menu bar
- Click "Settings" (gear icon)

### 2. Navigate to File Sharing
- Click "Resources" in the left sidebar
- Click "File Sharing" tab

### 3. Check Current Shared Paths
Look at the list of shared paths. You should see:
```
/Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7
```

**Important:** It must be the PARENT directory (`backend-v7`), NOT:
- ❌ `/Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7`
- ❌ `/Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laraBrowse`

### 4. If Path is Missing or Wrong

**Option A: Add the exact parent directory**
1. Click the **"+"** button
2. Type or paste this EXACT path:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7
   ```
3. Press Enter or click outside the field
4. Click **"Apply & Restart"** button (bottom right)
5. Wait for Docker to restart (check bottom bar shows "Engine running")

**Option B: Add parent parent directory (more flexible)**
1. Click the **"+"** button  
2. Type or paste:
   ```
   /Applications/XAMPP/xamppfiles/htdocs
   ```
3. Click **"Apply & Restart"**

### 5. Verify Docker Restarted
- Bottom bar should show "Engine running"
- No error messages

### 6. Test the Setup
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7
docker compose up -d
```

If successful, you should see:
```
[+] Running 3/3
✓ Container laravel7_mysql Running
✓ Container laravel7_phpmyadmin Running  
✓ Container laravel7_app Started
```

## Troubleshooting

**Still getting "mounts denied" error?**
1. Make sure you added the PARENT directory (`backend-v7`), not `laravel-7`
2. Make sure you clicked "Apply & Restart" and Docker fully restarted
3. Try adding `/Applications/XAMPP/xamppfiles/htdocs` instead (parent parent)
4. Restart Docker Desktop completely (quit and reopen)

**Path not showing in Docker Desktop?**
- Make sure the path exists: `ls /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7`
- Try typing the path manually instead of copy-paste
- Check for typos or extra spaces

