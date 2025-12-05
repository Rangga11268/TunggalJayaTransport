$envFile = ".env"
if (Test-Path $envFile) {
    $content = Get-Content $envFile | Where-Object { $_ -notmatch '^MIDTRANS_' }
    Set-Content $envFile $content
}
Add-Content $envFile "MIDTRANS_SERVER_KEY=Mid-server-q1kUecRxL9JDtnYIbxzPOHhZ"
Add-Content $envFile "MIDTRANS_CLIENT_KEY=MMid-client-aAUNIuf1fCSll2qz"
Add-Content $envFile "MIDTRANS_ENVIRONMENT=sandbox"
Add-Content $envFile "MIDTRANS_PAYMENT_URL=https://app.sandbox.midtrans.com"
Add-Content $envFile "MIDTRANS_API_URL=https://api.sandbox.midtrans.com/v2/"
Write-Host "Updated .env successfully"
