<?php

namespace App\Repositories;

use App\Models\Member;

class MemberRepository extends AbstractRepository
{
    public function save(Member $member): void
    {
        $members = $this->storage->load();

        $members[] = $member->toArray();

        $this->storage->save($members);
    }

    public function findAll(): array
    {
        $data = $this->storage->load();

        return array_map(
            fn(array $memberData): Member => Member::fromArray($memberData),
            $data
        );
    }

    public function findById(string $id): ?Member
    {
        $members = $this->findAll();

        for ($i = 0; $i < count($members); $i++) {
            if ($members[$i]->getId() === $id) {
                return $members[$i];
            }
        }

        return null;
    }

    public function update(Member $member): bool
    {
        $members = $this->findAll();

        for ($i = 0; $i < count($members); $i++) {
            if ($members[$i]->getId() === $member->getId()) {
                $members[$i] = $member;

                $data = array_map(
                    fn(Member $member): array => $member->toArray(),
                    $members
                );

                $this->storage->save($data);

                return true;
            }
        }

        return false;
    }

    public function delete(string $id): bool
    {
        $members = $this->findAll();

        $filteredMembers = array_filter(
            $members,
            fn(Member $member): bool => $member->getId() !== $id
        );

        if (count($members) === count($filteredMembers)) {
            return false;
        }

        $data = array_map(
            fn(Member $member): array => $member->toArray(),
            $filteredMembers
        );

        $this->storage->save(array_values($data));

        return true;
    }
}
