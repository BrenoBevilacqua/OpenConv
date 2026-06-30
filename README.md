
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Administrador Master',
    'email' => 'adminmaster@example.com',
    'username' => 'adminMaster',
    'password' => Hash::make('senha123'),
    'role' => User::ROLE_ADMIN_MASTER,
]);

- create file: backup/backup.sh

- content:
#!/bin/sh
while true; do
  mysqldump -h db -u 785632 -proot app-convenio > /backups/backup_$(date +%F_%H-%M-%S).sql
  find /backups -type f -mtime +7 -delete
  sleep 86400
done

