<?php

declare(strict_types=1);

namespace Damax\User\Domain\Model;

interface ActionRequestRepository
{
    public function byToken(string $token): ?ActionRequest;

    public function save(ActionRequest $request): void;

    public function remove(ActionRequest $request): void;
}
