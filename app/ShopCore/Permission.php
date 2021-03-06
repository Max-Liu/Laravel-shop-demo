<?php
namespace ShopCore;


class Permission
{
	protected $table = 'permissions';

	function __construct(permission\PermissionRepository $data, user\UserRepository $user, permission\GroupRepository $group)
	{
		$this->data = $data;
		$this->group = $group;
		$this->user = $user;
	}

	public function hasPermission($groupId, $currentRouteName)
	{
		$permissionInfo = $this->data->where('group_id', '=', $groupId)->get()->toArray();
		foreach ($permissionInfo as $permission) {
			$roles = unserialize($permission['roles']);
			foreach ($roles as $key => $role) {
				if ($permission['module'] . '.' . $key == $currentRouteName) {
					if ($role == 0) {
						return false;
					}
				}
			}
		}
		return true;
	}
}