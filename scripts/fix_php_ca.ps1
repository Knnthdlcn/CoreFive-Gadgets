$ErrorActionPreference = 'Stop'

$phpIni = 'C:\php85\php.ini'
$cacert = 'C:\php85\cacert.pem'

if (-not (Test-Path $phpIni)) {
  throw "php.ini not found at $phpIni"
}
if (-not (Test-Path $cacert)) {
  throw "CA bundle not found at $cacert"
}

$backup = "$phpIni.bak-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
Copy-Item -Force $phpIni $backup

$content = Get-Content -Raw -Path $phpIni

$patternCurl = '(?m)^\s*;?\s*curl\.cainfo\s*=.*$'
$patternOpenSsl = '(?m)^\s*;?\s*openssl\.cafile\s*=.*$'

$replacementCurl = "curl.cainfo=`"$cacert`""
$replacementOpenSsl = "openssl.cafile=`"$cacert`""

$content = [regex]::Replace($content, $patternCurl, $replacementCurl)
$content = [regex]::Replace($content, $patternOpenSsl, $replacementOpenSsl)

Set-Content -Path $phpIni -Value $content -Encoding ASCII

Write-Output "Updated $phpIni"
Write-Output "Backup created: $backup"
Write-Output "Set curl.cainfo + openssl.cafile to: $cacert"
