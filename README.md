# projet7API_Symfony

Installer la base de donnée Bilemo
avec les identifiants et mot de passe : root , root 


utiliser un server de php lorsque vous vous trouvez dans le dossier Bilemo 
en tapant la commande suivante dans le terminal : php -S localhost:8000 -t public

Pour utiliser l API et le jwt dans postman , effectuez une requete POST et placez vous sur l url :
http://localhost:8000/authentication_token et renseigner en JSON les identifiants suivants :
{
  "username": "AdminMan",
  "password": "undefined"
}

Vous pourrez ensuite a l'aide de ce token JWT vous connecter à l'api sur http://localhost:8000/api

