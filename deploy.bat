@echo off
echo ====================================================
echo Simple Payroll System - GitHub Push Helper
echo ====================================================
echo.
set /p REPO_URL="Enter your GitHub Repository URL (e.g. https://github.com/abadhbiharisd-98/spms.git): "
if "%REPO_URL%"=="" (
    echo Error: Repository URL cannot be empty.
    pause
    exit /b
)
git remote remove origin >nul 2>&1
git remote add origin %REPO_URL%
git branch -M main
echo Pushing to GitHub...
git push -u origin main
echo.
echo ====================================================
echo Code GitHub par upload ho gaya hai!
echo Ab https://dashboard.render.com par jakar Web Service deploy karein.
echo ====================================================
pause
