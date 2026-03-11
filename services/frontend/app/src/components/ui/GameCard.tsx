import React from 'react';

export interface CardData {
	id: number;
	category: string;
	rarity: 'common' | 'rare' | 'epic' | 'legendary';
	top: number | 'A';
	right: number | 'A';
	bottom: number | 'A';
	left: number | 'A';
}

interface GameCardProps {
	card: CardData;
	name: string;
	isUnlocked?: boolean;
}

const GameCard = ({ card, name, isUnlocked = false }: GameCardProps) => {
	const rarityGlow = {
		common: 'group-hover:shadow-[0_0_20px_rgba(70,25,1,0.8)] border-amber-950',
		rare: 'group-hover:shadow-[0_0_20px_rgba(82,82,92,0.6)] border-zinc-600',
		epic: 'group-hover:shadow-[0_0_20px_rgba(47,13,104,0.7)] border-violet-950',
		legendary: 'group-hover:shadow-[0_0_20px_rgba(166,95,0,0.8)] border-yellow-700'
	};

	return (
		<div className="relative group w-full aspect-2/3 rounded-sm cursor-pointer transition-transform duration-300 hover:-translate-y-2">

			{!isUnlocked ? (
				/* Blocked Card */
				<div className="absolute inset-0 bg-dark-900 overflow-hidden rounded-sm">
					<img
						src="/assets/cards/cardback.png"
						alt="Carta bloqueada"
						className="w-full h-full object-cover opacity-40 grayscale contrast-125 transition-opacity group-hover:opacity-60"
					/>
					<div className="absolute inset-0 flex items-center justify-center">
						<span className="text-4xl drop-shadow-[0_0_10px_rgba(0,0,0,0.8)]">🔒</span>
					</div>
				</div>
			) : (
				/* CARTA DESBLOQUEADA */
				// 2. CAMBIO AQUÍ: He añadido "overflow-hidden" a este div (el que tiene el border-2).
				// Ahora, cuando la imagen haga "scale-105", se recortará POR DENTRO del borde, sin taparlo nunca.
				<div className={`absolute inset-0 bg-dark-800 rounded-4xs border-2 overflow-hidden ${rarityGlow[card.rarity]} transition-all duration-300`}>

					{/* 1. ILUSTRACIÓN DE FONDO */}
					<img
						src={`/assets/cards/art/${card.id}.png`}
						alt={name}
						// Fallback por si falta alguna imagen
						onError={(e) => { (e.target as HTMLImageElement).src = `https://via.placeholder.com/400x600/1e293b/ffffff?text=Art+${card.id}`; }}
						className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
					/>

					{/* 2. MARCO PRE-ENSAMBLADO (PNG Transparente con marco, gema, rueda y fondo gris info) */}
					<img
						src={`/assets/cards/frames/frame_${card.rarity}.png`}
						alt={`Marco ${card.rarity}`}
						className="absolute inset-0 w-full h-full z-10 pointer-events-none"
					/>

					{/* 3. CAPA DE TEXTOS E INTERFAZ DINÁMICA (z-20) */}
					<div className="absolute inset-0 z-20 pointer-events-none">

						{/* Números de combate (Posicionados arriba a la izquierda) */}
						<div className="absolute top-1 left-1 w-10 h-10 lg:w-12 lg:h-12 font-bold text-white drop-shadow-[0_2px_2px_rgba(0,0,0,1)]">
							<span className="absolute top-0 left-1/2 -translate-x-1/2 text-xs lg:text-sm">{card.top}</span>
							<span className="absolute right-0 top-1/2 -translate-y-1/2 text-xs lg:text-sm">{card.right}</span>
							<span className="absolute bottom-0 left-1/2 -translate-x-1/2 text-xs lg:text-sm">{card.bottom}</span>
							<span className="absolute left-0 top-1/2 -translate-y-1/2 text-xs lg:text-sm">{card.left}</span>
						</div>

						{/* Nombre de la carta (Ajustado a la derecha de la rueda) */}
						<div className="absolute bottom-[3%] right-[4%] w-[68%] flex justify-center items-center h-[12%]">
							<h3 className="text-white font-vecna whitespace-nowrap text-[8px] sm:text-[9px] md:text-[11px] lg:text-[12px] xl:text-[13px] leading-none drop-shadow-[0_2px_4px_rgba(0,0,0,1)] text-center tracking-normal">
								{name}
							</h3>
						</div>

					</div>
				</div>
			)}
		</div>
	);
};

export default GameCard;