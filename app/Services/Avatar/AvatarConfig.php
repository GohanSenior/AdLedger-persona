<?php

class AvatarConfig
{
    public static array $common = [
        'size' => 128,
        'backgroundColor' => 'ed6da6',
        'radius' => 50,
        'accessoriesProbability' => 30,
        'mouth' => [
            'default',
            'smile',
            'serious',
            'twinkle'
        ],
        'eyes' => [
            'closed',
            'default',
            'eyeRoll',
            'happy',
            'side',
            'squint',
            'wink',
            'winkWacky'
        ],
        'skinColor' => [
            '614335',
            'ae5d29',
            'd08b5b',
            'edb98a',
            'f8d25c',
            'fd9841',
            'ffdbb4'
        ]
    ];

    public static array $male = [
        'top' => [
            'dreads01',
            'dreads02',
            'frizzle',
            'fro',
            'hat',
            'shaggy',
            'shaggyMullet',
            'shortCurly',
            'shortFlat',
            'shortRound',
            'shortWaved',
            'sides',
            'theCaesar',
            'theCaesarAndSidePart'
        ],
        'facialHair' => [
            'beardLight',
            'beardMajestic',
            'beardMedium',
            'moustacheFancy',
            'moustacheMagnum'
        ],
        'facialHairProbability' => 50,
        'accessories' => [
            'prescription01',
            'prescription02',
            'round',
            'sunglasses',
            'wayfarers'
        ]
    ];

    public static array $female = [
        'top' => [
            'bigHair',
            'bob',
            'bun',
            'curly',
            'curvy',
            'dreads',
            'frida',
            'froBand',
            'longButNotTooLong',
            'miaWallace',
            'shavedSides',
            'straight01',
            'straight02',
            'straightAndStrand'
        ],
        'facialHairProbability' => 0,
        'accessories' => [
            'kurt',
            'prescription01',
            'prescription02',
            'round',
            'sunglasses',
            'wayfarers'
        ]
    ];

    public static array $neutral = [
        'facialHairProbability' => 50,
        'eyes' => [
            'closed',
            'default',
            'eyeRoll',
            'happy',
            'side',
            'squint',
            'wink',
            'winkWacky'
        ],
        'accessories' => [
            'kurt',
            'prescription01',
            'prescription02',
            'round',
            'sunglasses',
            'wayfarers'
        ]
    ];
}
