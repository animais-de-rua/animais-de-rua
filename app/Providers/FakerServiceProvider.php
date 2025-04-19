<?php

namespace App\Providers;

use Faker\Provider\Base;

class FakerServiceProvider extends Base
{
    /**
     * @var array<string> Animal names
     */
    public array $animal_names = [
        'Alecrim', 'Alex', 'Alice', 'Amarelinho', 'Ambar',
        'Amendoim', 'Archie', 'Arcol', 'Artemisa', 'Ary',
        'Arya', 'Atena', 'Aurora', 'Babalu', 'Barbudo',
        'Bea', 'Biscoito', 'Chico', 'Cinza', 'Codi',
        'Cookie', 'Denver', 'Dona Xica', 'Elefante', 'Estrela',
        'Felicia', 'Flor', 'Florbela', 'Francisca', 'Fuji',
        'Gaspar', 'Gaya', 'Gil', 'Jade', 'Jecky',
        'Johnny', 'Joy', 'Kat', 'Kedi', 'Kid',
        'Kit', 'Kyra', 'Leggy', 'Leica', 'Lia',
        'Luana', 'Luca', 'Lucky', 'Luna', 'Mamy',
        'Matias', 'Melba', 'Miona', 'Neruda', 'Nikita',
        'Oliver', 'Oscar', 'Pakora', 'Panti', 'Pança',
        'Ping', 'Pirata', 'Plaza', 'Pong', 'Porto',
        'Queen', 'Ravi', 'Robin', 'Rufi', 'Sancho',
        'Simba', 'Sophie', 'Tareka', 'Tita', 'Trevo',
        'Tricolor', 'Vasco', 'Yoko', 'Zenit', 'Óscar',
    ];

    /**
     * @var array<string> District names
     */
    public array $districts = [
        'Aveiro', 'Beja', 'Braga', 'Bragança', 'Castelo Branco',
        'Coimbra', 'Évora', 'Faro', 'Guarda', 'Leiria',
        'Lisboa', 'Portalegre', 'Porto', 'Santarém', 'Setúbal',
        'Viana do Castelo', 'Vila Real', 'Viseu', 'Ilha da Madeira', 'Ilha de Porto Santo',
        'Ilha de Santa Maria', 'Ilha de São Miguel', 'Ilha Terceira', 'Ilha Graciosa', 'Ilha de São Jorge',
        'Ilha do Pico', 'Ilha do Faial', 'Ilha das Flores', 'Ilha do Corvo',
    ];

    public function animal(): string
    {
        return $this->animal_names[rand(0, count($this->animal_names) - 1)];
    }

    public function district(): string
    {
        return $this->districts[rand(0, count($this->districts) - 1)];
    }
}
