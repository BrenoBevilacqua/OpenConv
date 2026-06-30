while true; do
  mysqldump -h db -u root -p785632 app-convenio > /backups/backup_$(date +%F_%H-%M-%S).sql
  find /backups -type f -mtime +7 -delete
  sleep 86400
done
