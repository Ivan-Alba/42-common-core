export type CardRarity = 'common' | 'rare' | 'epic' | 'legendary';

export interface CardData {
	id: number;
	name: string;
	description: string;
	category: string;
	rarity: CardRarity;
	front_image?: string;
	back_image?: string;
	stats: {
		top: number | 'A';
		right: number | 'A';
		bottom: number | 'A';
		left: number | 'A';
	};
}
