<?php

namespace App\Enums;

enum GameParamType: string
{
    case CASH = 'cash';
    case RESPECT = 'respect';
    case HOOKERS_COUNT = 'hookers_count';
    case STATS_TOTAL = 'stats_total';
    case DRUG_SOLD = 'drug_sold';
    case EQUIPMENT_OWNED = 'equipment_owned';
    case HOOKER_TYPE_OWNED = 'hooker_type_owned';
    case KILLS_COUNT = 'kills_count';
    case SINGLE_ROBBERY_COUNT = 'single_robbery_count';
    case EQUIPMENT_ACTIVE = 'equipment_active';
    case AVAILABLE_STATS = 'available_stats';
    case DRUG_RECEIVED = 'drug_received';
    case EQUIPMENT_RECEIVED = 'equipment_received';
    case HOOKER_RECEIVED = 'hooker_received';
    case COMPONENT_RECEIVED = 'component_received';
    case STAMINA = 'stamina';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Dinheiro',
            self::RESPECT => 'Respeito',
            self::HOOKERS_COUNT => 'Prostitutas Contratadas',
            self::STATS_TOTAL => 'Atributos Totais',
            self::DRUG_SOLD => 'Droga Vendida',
            self::EQUIPMENT_OWNED => 'Equipamento Possuído',
            self::HOOKER_TYPE_OWNED => 'Garotas do Tipo',
            self::KILLS_COUNT => 'Assassinatos',
            self::SINGLE_ROBBERY_COUNT => 'Assaltos Solo Realizados',
            self::EQUIPMENT_ACTIVE => 'Equipamento Ativado',
            self::AVAILABLE_STATS => 'Atributos Livres',
            self::DRUG_RECEIVED => 'Droga Recebida',
            self::EQUIPMENT_RECEIVED => 'Equipamento Recebido',
            self::HOOKER_RECEIVED => 'Prostituta Recebida',
            self::COMPONENT_RECEIVED => 'Componente Recebido',
            self::STAMINA => 'Energia (Stamina)',
        };
    }

    public static function getLabel(string $value): string
    {
        $enum = self::tryFrom($value);
        return $enum ? $enum->label() : ucwords(str_replace('_', ' ', $value));
    }
}
