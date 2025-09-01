#!/bin/bash
echo "debut du backup" >> storage/logs/laravel.log
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR=/var/backups/easygest
mkdir -p $BACKUP_DIR
mysqldump -u easygest -p'E@syGest2025' EasyGest> $BACKUP_DIR/easygest_backup_$DATE.sql
rclone copy $BACKUP_DIR/easygest_backup_$DATE.sql "easygest_backup_th_damas:"
echo "fin du backup" >> storage/logs/laravel.log
