<?php

namespace App;

enum ArtworkStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case RESERVED = 'reserved';
    case SOLD = 'sold';
}
