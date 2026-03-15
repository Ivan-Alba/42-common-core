import React, { useState } from 'react';
import { createPortal } from 'react-dom';
// IMPORTAMOS LA INTERFAZ CENTRALIZADA
import type { CardData } from '../../models/CardData';

// Ya no necesitamos definir CardData aquí. Solo los Props del componente.
interface GameCardProps {
    card: CardData;
    isUnlocked?: boolean;
}

const GameCard = ({ card, isUnlocked = false }: GameCardProps) => {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isSpinning, setIsSpinning] = useState(false);

    const rarityGlow = {
        common: 'group-hover:shadow-[0_0_20px_rgba(70,25,1,0.8)] border-amber-950',
        rare: 'group-hover:shadow-[0_0_20px_rgba(82,82,92,0.6)] border-zinc-600',
        epic: 'group-hover:shadow-[0_0_20px_rgba(47,13,104,0.7)] border-violet-950',
        legendary: 'group-hover:shadow-[0_0_20px_rgba(166,95,0,0.8)] border-yellow-700'
    };

    const handleCardClick = () => {
        if (!isUnlocked) return;
        setIsModalOpen(true);
        setIsSpinning(true);
        
        setTimeout(() => setIsSpinning(false), 1200);
    };

    const CardFace = (
        <div className={`absolute inset-0 bg-dark-800 rounded-4xs border-2 overflow-hidden ${rarityGlow[card.rarity]} transition-all duration-300`}>
            <img src={`/assets/cards/art/${card.id}.png`} alt={card.name} className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <img src={`/assets/cards/frames/frame_${card.rarity}.png`} alt={`Marco ${card.rarity}`} className="absolute inset-0 w-full h-full z-10 pointer-events-none" />
            
            <div className="absolute inset-0 z-20 pointer-events-none">
                <div className="absolute top-[9%] left-[11%] w-[20%] aspect-square grid grid-cols-3 grid-rows-3 items-center justify-items-center font-vecna-bold text-white drop-shadow-[0_4px_4px_rgb(0,0,0)] text-[13.5cqi]">
                    <span className="col-start-2 row-start-1 leading-none -translate-y-[22%]">{card.stats.top}</span>
                    <span className="col-start-1 row-start-2 leading-none -translate-x-[22%]">{card.stats.left}</span>
                    <span className="col-start-3 row-start-2 leading-none translate-x-[22%]">{card.stats.right}</span>
                    <span className="col-start-2 row-start-3 leading-none translate-y-[22%]">{card.stats.bottom}</span>
                </div>
                
                <div className="absolute bottom-[4%] right-[7%] w-[70%] flex justify-center items-center h-[12%] px-2">
                    <h3 className="text-white font-vecna text-[7.5cqi] leading-[0.90] pt-0.5 drop-shadow-[0_2px_4px_rgba(0,0,0,1)] text-center text-balance line-clamp-2">
                        {card.name}
                    </h3>
                </div>
            </div>
        </div>
    );

    return (
        <div onClick={handleCardClick} className="relative group w-full aspect-2/3 rounded-sm cursor-pointer transition-transform duration-300 hover:-translate-y-2 [container-type:inline-size]">
            
            {!isUnlocked ? (
                <div className="absolute inset-0 bg-dark-900 overflow-hidden rounded-sm">
                    <img src="/assets/cards/cardback.png" alt="Carta bloqueada" className="w-full h-full object-cover opacity-40 grayscale contrast-125 transition-opacity group-hover:opacity-60" />
                    <div className="absolute inset-0 flex items-center justify-center">
                        <img src="/assets/cards/lock.png" alt="Locked" className="w-16 h-16 object-contain drop-shadow-[0_0_10px_rgba(0,0,0,0.8)] z-30" />
                    </div>
                </div>
            ) : (
                CardFace
            )}

            {isModalOpen && createPortal(
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 backdrop-blur-sm p-4 sm:p-10 animate-fade-in" onClick={(e) => { e.stopPropagation(); setIsModalOpen(false); }}>
                    
                    <button className="absolute top-6 right-6 text-white/50 hover:text-white transition-colors text-4xl font-bold z-50">
                        &times;
                    </button>

                    <div className="max-w-6xl w-full flex flex-col md:flex-row gap-8 lg:gap-16 items-center justify-center" onClick={(e) => e.stopPropagation()}>
                        
                        <div className={`relative w-[280px] sm:w-[350px] md:w-[450px] aspect-2/3 shrink-0 [container-type:inline-size] ${isSpinning ? 'animate-fly' : ''}`} style={{ perspective: '1500px' }}>
                            <div className={`relative w-full h-full ${isSpinning ? 'animate-spin-3d' : ''}`} style={{ transformStyle: 'preserve-3d' }}>
                                
                                <div className="absolute inset-0" style={{ WebkitBackfaceVisibility: 'hidden', backfaceVisibility: 'hidden', transform: 'rotateY(0deg)' }}>
                                    {CardFace}
                                </div>

                                <div className="absolute inset-0" style={{ WebkitBackfaceVisibility: 'hidden', backfaceVisibility: 'hidden', transform: 'rotateY(180deg)' }}>
                                    <div className="absolute inset-0 bg-dark-900 rounded-sm overflow-hidden border border-white/10 shadow-[0_0_30px_rgba(0,0,0,0.8)]">
                                        <img src="/assets/cards/cardback.png" alt="Reverso" className="w-full h-full object-cover opacity-80" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div className="flex-1 text-left bg-dark-800/60 p-6 md:p-10 rounded-2xl border border-white/10 shadow-[0_0_40px_rgba(0,0,0,0.5)] animate-fade-in-up delay-700">
                            
                            <div className="flex items-center gap-3 mb-4">
                                <span className="px-3 py-1 bg-dark-900 border border-white/10 text-slate-300 text-xs uppercase tracking-wider font-bold rounded-full">
                                    {card.category}
                                </span>
                                <span className={`px-3 py-1 bg-dark-900 border text-xs uppercase tracking-wider font-bold rounded-full ${
                                    card.rarity === 'common' ? 'text-amber-700 border-amber-900/50' :
                                    card.rarity === 'rare' ? 'text-zinc-400 border-zinc-600/50' :
                                    card.rarity === 'epic' ? 'text-violet-400 border-violet-900/50' :
                                    'text-yellow-500 border-yellow-700/50'
                                }`}>
                                    {card.rarity}
                                </span>
                            </div>

                            <h2 className="font-vecna text-4xl md:text-5xl lg:text-6xl text-white mb-6 drop-shadow-md">
                                {card.name}
                            </h2>

                            <div className="h-px w-full bg-gradient-to-r from-brand-500/50 to-transparent mb-6"></div>

                            <p className="text-slate-300 text-lg md:text-xl leading-relaxed font-sans italic">
                                "{card.description}"
                            </p>
                        </div>
                    </div>
                </div>,
                document.body
            )}
        </div>
    );
};

export default GameCard;