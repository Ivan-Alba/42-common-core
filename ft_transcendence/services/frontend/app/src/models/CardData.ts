export type CardRarity = 'common' | 'rare' | 'epic' | 'legendary';

export interface CardData {
	id: number;
	name: string;
	description: string;
	category: string;
	rarity: CardRarity;
	blue_artwork?: string;
    red_artwork?: string;
	blue_url?: string;
	red_url?: string;
	stats: {
		top: number | 'A';
		right: number | 'A';
		bottom: number | 'A';
		left: number | 'A';
	};
}
