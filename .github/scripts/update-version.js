const fs = require('fs');
const path = require('path');

// Path to src/Client.php file
const clientFilePath = path.join(process.cwd(), 'src', 'Client.php');

// Read the file
let content = fs.readFileSync(clientFilePath, 'utf8');

// Regular expression to extract version information
const versionRegex = /public const VERSION = '([0-9]+\.[0-9]+\.[0-9]+)';/;
const match = content.match(versionRegex);

if (!match) {
    console.error('Version information not found.');
    process.exit(1);
}

// Current version
const currentVersion = match[1];
console.log(`Current version: ${currentVersion}`);

// Parse version and increment patch version
const [major, minor, patch] = currentVersion.split('.').map(Number);
const newVersion = `${major}.${minor}.${patch + 1}`;
console.log(`New version: ${newVersion}`);

// Update version information
const updatedContent = content.replace(versionRegex, `public const VERSION = '${newVersion}';`);

// Write to file
fs.writeFileSync(clientFilePath, updatedContent);

console.log(`Version updated from ${currentVersion} to ${newVersion}.`);

// Pass version information to GitHub Actions
// Using the new GITHUB_OUTPUT environment file syntax
if (process.env.GITHUB_OUTPUT) {
    fs.appendFileSync(process.env.GITHUB_OUTPUT, `version=${newVersion}\n`);
} else {
    console.log(`::set-output name=version::${newVersion}`);
}
