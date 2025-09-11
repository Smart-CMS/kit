<?php

namespace SmartCms\Kit\Services;

use Illuminate\Support\Facades\Log;

class UpdateErrorHandler
{
    public static function handleComposerError(string $errorOutput, int $exitCode): array
    {
        $errorType = self::identifyComposerErrorType($errorOutput);
        $userMessage = self::getComposerUserMessage($errorType, $errorOutput);
        $troubleshooting = self::getComposerTroubleshooting($errorType);

        Log::error('Composer update failed', [
            'error_type' => $errorType,
            'exit_code' => $exitCode,
            'error_output' => $errorOutput
        ]);

        return [
            'type' => $errorType,
            'message' => $userMessage,
            'troubleshooting' => $troubleshooting,
            'technical_details' => $errorOutput
        ];
    }

    public static function handleGithubError(\Exception $e): array
    {
        $errorType = self::identifyGithubErrorType($e);
        $userMessage = self::getGithubUserMessage($errorType, $e);
        $troubleshooting = self::getGithubTroubleshooting($errorType);

        Log::warning('GitHub API error handled', [
            'error_type' => $errorType,
            'message' => $e->getMessage()
        ]);

        return [
            'type' => $errorType,
            'message' => $userMessage,
            'troubleshooting' => $troubleshooting,
            'technical_details' => $e->getMessage()
        ];
    }

    protected static function identifyComposerErrorType(string $errorOutput): string
    {
        $patterns = [
            'dependency_conflict' => [
                'Your requirements could not be resolved',
                'Conclusion: don\'t install',
                'conflicting requirements'
            ],
            'permission_denied' => [
                'Permission denied',
                'cannot create directory',
                'failed to open stream: Permission denied'
            ],
            'network_error' => [
                'Could not fetch',
                'curl error',
                'Connection timed out',
                'Failed to download'
            ],
            'memory_limit' => [
                'Fatal error: Allowed memory size',
                'out of memory'
            ],
            'composer_not_found' => [
                'composer: command not found',
                'composer is not recognized'
            ],
            'lock_file_error' => [
                'composer.lock is not up to date',
                'Hash mismatch'
            ]
        ];

        foreach ($patterns as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($errorOutput, $keyword) !== false) {
                    return $type;
                }
            }
        }

        return 'unknown';
    }

    protected static function identifyGithubErrorType(\Exception $e): string
    {
        $message = $e->getMessage();

        if (strpos($message, 'rate limit') !== false) {
            return 'rate_limit';
        }
        if (strpos($message, 'connection') !== false || strpos($message, 'connect') !== false) {
            return 'connection_error';
        }
        if (strpos($message, 'Repository not found') !== false) {
            return 'repository_not_found';
        }
        if (strpos($message, 'Access denied') !== false) {
            return 'access_denied';
        }
        if (strpos($message, 'unavailable') !== false) {
            return 'service_unavailable';
        }

        return 'unknown';
    }

    protected static function getComposerUserMessage(string $errorType, string $errorOutput): string
    {
        switch ($errorType) {
            case 'dependency_conflict':
                return 'Update failed due to package dependency conflicts. Some packages have incompatible version requirements.';
            case 'permission_denied':
                return 'Update failed due to insufficient file permissions. The web server needs write access to the project directory.';
            case 'network_error':
                return 'Update failed due to network connectivity issues. Please check your internet connection and try again.';
            case 'memory_limit':
                return 'Update failed due to insufficient memory. Please increase PHP memory limit or run the update via command line.';
            case 'composer_not_found':
                return 'Composer is not installed or not accessible. Please install Composer or check your PATH configuration.';
            case 'lock_file_error':
                return 'Composer lock file is out of sync. Please run "composer install" first or delete composer.lock and try again.';
            default:
                return 'Update failed due to an unexpected error. Please check the technical details below.';
        }
    }

    protected static function getGithubUserMessage(string $errorType, \Exception $e): string
    {
        switch ($errorType) {
            case 'rate_limit':
                return 'GitHub API rate limit exceeded. Please wait before checking for updates again.';
            case 'connection_error':
                return 'Unable to connect to GitHub. Please check your internet connection.';
            case 'repository_not_found':
                return 'The configured repository was not found on GitHub. Please check the repository configuration.';
            case 'access_denied':
                return 'Access denied to the GitHub repository. This might be due to rate limiting or repository permissions.';
            case 'service_unavailable':
                return 'GitHub API is currently unavailable. Please try again later.';
            default:
                return 'Failed to fetch update information from GitHub: ' . $e->getMessage();
        }
    }

    protected static function getComposerTroubleshooting(string $errorType): array
    {
        switch ($errorType) {
            case 'dependency_conflict':
                return [
                    'Run "composer update --dry-run" to see detailed conflict information',
                    'Check if any packages have been manually modified',
                    'Consider updating conflicting packages individually',
                    'Review composer.json for version constraints that might be too strict'
                ];
            case 'permission_denied':
                return [
                    'Ensure the web server user has write permissions to the project directory',
                    'Check ownership of files and directories (should match web server user)',
                    'Run "chmod -R 755" on the project directory if needed',
                    'Consider running the update as the correct user via command line'
                ];
            case 'network_error':
                return [
                    'Check your internet connection',
                    'Verify that your server can access external websites',
                    'Check if there are any firewall restrictions',
                    'Try running the update manually via command line'
                ];
            case 'memory_limit':
                return [
                    'Increase PHP memory_limit in php.ini (recommended: 512M or higher)',
                    'Run the update via command line which typically has higher memory limits',
                    'Consider using "composer update --no-dev" to reduce memory usage',
                    'Close other applications to free up system memory'
                ];
            case 'composer_not_found':
                return [
                    'Install Composer from https://getcomposer.org/',
                    'Ensure Composer is in your system PATH',
                    'Try running "which composer" to verify installation',
                    'Consider using the full path to composer executable'
                ];
            case 'lock_file_error':
                return [
                    'Run "composer install" to sync the lock file',
                    'Delete composer.lock and run "composer update" to regenerate it',
                    'Ensure composer.json and composer.lock are both committed to version control',
                    'Check if composer.json was modified without updating the lock file'
                ];
            default:
                return [
                    'Check the technical details for specific error information',
                    'Try running the update manually via command line for more detailed output',
                    'Ensure all system requirements are met',
                    'Contact support if the issue persists'
                ];
        }
    }

    protected static function getGithubTroubleshooting(string $errorType): array
    {
        switch ($errorType) {
            case 'rate_limit':
                return [
                    'Wait for the rate limit to reset (usually within an hour)',
                    'Consider configuring a GitHub personal access token for higher limits',
                    'Reduce the frequency of update checks in configuration',
                    'Use manual update checking instead of automatic checks'
                ];
            case 'connection_error':
                return [
                    'Check your internet connection',
                    'Verify DNS resolution is working',
                    'Check if GitHub.com is accessible from your server',
                    'Review firewall and proxy settings'
                ];
            case 'repository_not_found':
                return [
                    'Verify the repository name in kit.php configuration',
                    'Ensure the repository exists and is public',
                    'Check for typos in the repository path',
                    'Confirm the repository has releases published'
                ];
            case 'access_denied':
                return [
                    'Wait if this is due to rate limiting',
                    'Check if the repository is public',
                    'Verify repository permissions if using authentication',
                    'Try again later as this might be temporary'
                ];
            case 'service_unavailable':
                return [
                    'Wait and try again later',
                    'Check GitHub status at https://www.githubstatus.com/',
                    'Use manual update checking if automatic checks fail',
                    'Consider increasing timeout settings'
                ];
            default:
                return [
                    'Check the technical details for specific error information',
                    'Verify GitHub repository configuration',
                    'Try manual update checking',
                    'Contact support if the issue persists'
                ];
        }
    }
}
