<?php

namespace SmartCms\Kit\Contracts;

interface UpdateCheckerInterface
{
    /**
     * Check for updates when admin logs in.
     */
    public function checkOnLogin(): void;

    /**
     * Determine if update checking should be performed.
     */
    public function shouldCheck(): bool;

    /**
     * Store update notification for display in admin panel.
     */
    public function storeUpdateNotification(array $updateInfo): void;

    /**
     * Get stored update notifications.
     */
    public function getUpdateNotifications(): ?array;

    /**
     * Clear stored update notifications.
     */
    public function clearUpdateNotifications(): void;
}
