# mediatekformation
## Atelier réalisé dans la cadre de ma formation au BTS SIO
Dans le cadre de ma formation pour le BTS SIO: option SLAM, j'ai réalisé cet atelier de professionnalisation visant à faire évoluer une application préexistante fournie par le CNED. 

### Partie front
L'application "mediatekformation" permet d'une part, dans la partie front, de visionner des formations qui ont été aupréalablement ajoutées. Une formation comprend une vidéo, un titre, une description, un niveau de difficulté, une date de parution, une image illustrant la formation et une miniature.
La liste de formations peut être triée par titre et par date de parution. Il est aussi possible de filtrer les formations en écrivant un titre à la main, ou en choisissant un niveau pour n'afficher que les formations correspondantes. 
### Partie back-office
D'autre part, l'application comprend un back-office, permettant de gérer l'ajout, la modification et la suppression de formations. Il faut être authentifié pour accéder au back-office.  
Cette partie admin permet en outre d'ajouter et de supprimer des niveaux de difficulté. Un niveau ayant des formations associé ne peut pas être supprimé.  
L'ensemble des requêtes effectuées par l'application emploient des tokens pour contrer la CSRF et les requêtes SQL sont paramétrées pour contrer les SQLi. Par ailleurs les valeurs des formulaires ayant une répercution sur les données de la base de données sont testées avant l'envoi de la requête. 
