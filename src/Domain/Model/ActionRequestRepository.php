<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

use Ramsey\Uuid\UuidInterface;

interface ActionRequestRepository
{
    public function byToken(string $token): ?ActionRequest;

    /**
     * @return ActionRequest[]
     */
    public function byUserId(UuidInterface $userId): array;

    public function save(ActionRequest $request): void;

    public function remove(ActionRequest $request): void;
}
