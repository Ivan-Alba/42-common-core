export type CardRarity = 'common' | 'rare' | 'epic' | 'legendary';

export interface GameCard {
    id: number; // En Laravel es un ID numérico
    name: string;
    description: string; // <-- Nueva propiedad para las traducciones
    rarity: CardRarity;
    // cost: number; // Nota: Si no lo envías desde el Resource, ella no lo recibirá.
    front_image: string; // Coincide con tu Resource
    back_image: string;  // Coincide con tu Resource
    stats: {
        top: number;
        right: number;
        bottom: number;
        left: number;
    };
}