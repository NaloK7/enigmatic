# Objectif : Créer une image exécutable directement dans un serveur docker 
# Comment : Via les actions github et un Dockerfile. Il est également possible de tester en local

#############################################################
#################	Commandes du Dockerfile	#################
############################################################# 
# FROM php-8.4 : Définit l'image de base a utiliser pour construire l'image finale
# RUN macommande --with parameters : Exécuter une commande avec des paramètres (commandes que tu peux rentrer dans ton terminal par ex) 
# COPY path/from/source.php /path/to/dest/on/container: Copie les fichiers du répertoire local pour les intégrer dans l'image finale
# WORKDIR /path/to/dir : Dossier de base de l'image. Quand tu vas te connecter a l'image, dans quel dossier tu vas arriver
# CMD ['npm', 'run', 'dev'] : La commande a exécuter lorsque le conteneur se lancera 
# EXPOSE 443 : Définit un port a exposer. Ce port est accessible entre l'host et le conteneur docker
# Documentation : https://docs.docker.com/reference/dockerfile/

#############################################################
#################	Commandes du Utiles		#################
############################################################# 
#Une fois le build terminé, tu peux exécuter cette commande (via ton terminal WSL) et naviguer dans les dossiers pour voir le résultat
#docker-compose run --rm --user=node nodejs sh

#Sortir du conteneur : exit

# Démarrer les conteneurs contenus dans le dockerfile : 
# docker-compose up -d 
# Attention, 2 conteneurs docker ne peuvent pas utiliser le même port !!! 

# Les stopper : 
# docker-compose down

# Voir leur état : 
# docker-compose ps 

# Voir les logs : 
# docker-compose logs -f 


#################################################
#################	PRATIQUE	#################
#################################################
#Définir l'image de base pour le front 
FROM node:18
# Définir le répertoire principal de travail (généralement pour node /app)("/" en début de chemin représente la racine)
WORKDIR /frontend
# Copier tous les fichiers nécessaires dans l'image 
COPY ./frontend /frontend
# Installer les modules node
RUN npm install
# Compiler le projet pour la production 
RUN npm run build

EXPOSE 3000
# Définir la commande d'exécution du lancement 
CMD ["npm", "run", "start"]
