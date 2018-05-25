<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new GenerateBillingsCommand());
Artisan::add(new CreateGenerateAdminBillingsCommand());
Artisan::add(new BackupAndDropboxCommand());
Artisan::add(new RemainderUncreatedPublishersCommand());
Artisan::add(new PublishersOptimizationCommand());
Artisan::add(new CategorizeCommand());
Artisan::add(new InventoryCommand());
Artisan::add(new MigrateFromYaxCommand());
Artisan::add(new AddDefaultTagsCommand());
Artisan::add(new ChangeAppnexusSiteOptionsCommand());
Artisan::add(new CreatePublishersAdserverCommand());
Artisan::add(new CreateCalculateAdminActualBalanceCommand());
Artisan::add(new CreateControlCTRCommand());
Artisan::add(new CreateControlPlacementsCommand());
Artisan::add(new ReassignExecutiveCommand());