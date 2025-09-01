uid308541@h2web437:~/sites/ucbinvestissement.com$ cat run_shedule.sh 
#!/bin/bash

# Indiquer le chemin vers votre application Laravel et le fichier artisan
APP_PATH="~/sites/ucbinvestissement.com"
ARTISAN="$APP_PATH/artisan"

# Boucle infinie pour exécuter la commande toutes les minutes
while true
do
    # Exécuter la commande php artisan schedule:run
    php82 $ARTISAN schedule:run

    # Attendre une minute (60 secondes)
    sleep 60
done
