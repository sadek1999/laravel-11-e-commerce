<?php

namespace Database\Seeders;

use App\Enum\PermissionsEnum;
use App\Enum\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole=Role::create(['name'=>RolesEnum::Admin->value]);
        $vendorRole=Role::create(['name'=>RolesEnum::Vendor->value]);
        $userRole=Role::create(['name'=>RolesEnum::User->value]);

        $approveVendorsPermission=Permission::create(['name'=>PermissionsEnum::ApproveVendors->value]);
        $sellProductsPermission=Permission::create(['name'=>PermissionsEnum::SellProducts->value]);
        $buyProductsPermission=Permission::create(['name'=>PermissionsEnum::BuyProducts->value]);

        $userRole->syncPermissions([$buyProductsPermission]);
        $vendorRole->syncPermissions([$buyProductsPermission,$sellProductsPermission]);
        $adminRole->syncPermissions([$buyProductsPermission,$sellProductsPermission,$approveVendorsPermission]);

        
    }
}
