$ErrorActionPreference = 'Stop'

$root = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$dist = Join-Path $root 'dist'
$zipPath = Join-Path $dist 'ProfilKampungMbu-ready.zip'

$excludeDirs = @(
    '.git',
    '.vscode',
    '.idea',
    'node_modules',
    'dist',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs'
)

$excludeFiles = @(
    '.env',
    '.env.backup',
    '.env.production',
    '.phpunit.result.cache',
    'auth.json',
    'database/database.sqlite'
)

function Get-RelativePath([string] $fullPath) {
    return $fullPath.Substring($root.Length).TrimStart('\', '/') -replace '\\', '/'
}

function Test-IsExcluded([string] $fullPath) {
    $relative = Get-RelativePath $fullPath

    foreach ($dir in $excludeDirs) {
        $normalized = $dir -replace '\\', '/'

        if ($relative -eq $normalized -or $relative.StartsWith($normalized + '/')) {
            return $true
        }
    }

    foreach ($file in $excludeFiles) {
        if ($relative -eq ($file -replace '\\', '/')) {
            return $true
        }
    }

    return $false
}

if (-not (Test-Path -LiteralPath $dist)) {
    New-Item -ItemType Directory -Path $dist | Out-Null
}

if (Test-Path -LiteralPath $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$zip = [System.IO.Compression.ZipFile]::Open($zipPath, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    $files = Get-ChildItem -LiteralPath $root -Recurse -File -Force |
        Where-Object {
            -not ($_.Attributes -band [System.IO.FileAttributes]::ReparsePoint) -and
            -not (Test-IsExcluded $_.FullName)
        }

    foreach ($file in $files) {
        $entryName = Get-RelativePath $file.FullName
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $zip,
            $file.FullName,
            $entryName,
            [System.IO.Compression.CompressionLevel]::Fastest
        ) | Out-Null
    }
} finally {
    $zip.Dispose()
}

Write-Host "ZIP siap jalan dibuat:"
Write-Host $zipPath
