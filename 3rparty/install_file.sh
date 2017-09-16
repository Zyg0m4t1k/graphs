#!/bin/bash
echo $1
cd ../
if [ -d "data" ];then
 echo "Le dossier data existe deja !";
else
echo 'Création des fichiers'
sudo mkdir 'data'
cd 'data'
sudo touch 'year.json'
sudo touch 'month.json'
echo 'Fichiers crées'
fi