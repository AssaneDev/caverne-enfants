<?php

namespace App\Helpers;

class QuoteHelper
{
    public static function getRandomQuote()
    {
        $quotes = [
            "Des artistes et des enfants unissent leurs gestes, la couleur devient poésie, la nature devient mémoire, et le Sénégal s'illumine dans chaque trait.",
            "Les couleurs deviennent langage, la peinture, un pont entre les cœurs. Nous avons fusionné ensemble des mois durant pour ce miracle.",
            "Chaque œuvre est un duo, une main tendue, un souffle de créativité partagée. Ici, l'art est un bien grand mot qui devient une émergence.",
            "L'eau est omniprésente dans chaque création : elle irrigue cette région par une crue providentielle chaque année. Sur les tableaux, des formes de feuilles et de gouttes parlent de cette nature sauvage.",
            "Parler du Sénégal, c'est parler d'un petit pays par sa taille et d'un géant par sa culture et sa générosité."
        ];

        return $quotes[array_rand($quotes)];
    }
}
