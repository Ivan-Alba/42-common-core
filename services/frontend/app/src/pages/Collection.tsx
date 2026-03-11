import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import DashboardLayout from '../components/layouts/DashboardLayout';
import GameCard, {type CardData } from '../components/ui/GameCard';

// Base de datos estática temporal (Luego vendrá de la API)
const CARDS_DATABASE: CardData[] = [
    { id: 1, category: 'human', rarity: 'common', top: 5, right: 3, bottom: 4, left: 3 },
    { id: 2, category: 'human', rarity: 'common', top: 2, right: 6, bottom: 2, left: 6 },
    { id: 3, category: 'human', rarity: 'rare', top: 7, right: 4, bottom: 2, left: 6 },
    { id: 4, category: 'human', rarity: 'rare', top: 3, right: 5, bottom: 7, left: 4 },
    { id: 5, category: 'human', rarity: 'epic', top: 8, right: 2, bottom: 7, left: 6 },
    { id: 6, category: 'human', rarity: 'legendary', top: 'A', right: 4, bottom: 8, left: 5 },
    
    { id: 7, category: 'animal', rarity: 'common', top: 6, right: 2, bottom: 5, left: 3 },
    { id: 8, category: 'animal', rarity: 'common', top: 4, right: 5, bottom: 3, left: 5 },
    { id: 9, category: 'animal', rarity: 'common', top: 3, right: 4, bottom: 6, left: 2 },
    { id: 10, category: 'animal', rarity: 'rare', top: 5, right: 7, bottom: 4, left: 3 },
    { id: 11, category: 'animal', rarity: 'epic', top: 2, right: 8, bottom: 5, left: 8 },
    { id: 12, category: 'animal', rarity: 'legendary', top: 6, right: 9, bottom: 3, left: 'A' },
    
    { id: 13, category: 'beast', rarity: 'common', top: 2, right: 7, bottom: 2, left: 4 },
    { id: 14, category: 'beast', rarity: 'rare', top: 6, right: 6, bottom: 6, left: 2 },
    { id: 15, category: 'beast', rarity: 'rare', top: 4, right: 3, bottom: 8, left: 5 },
    { id: 16, category: 'beast', rarity: 'epic', top: 8, right: 6, bottom: 2, left: 8 },
    { id: 17, category: 'beast', rarity: 'epic', top: 4, right: 8, bottom: 8, left: 4 },
    { id: 18, category: 'beast', rarity: 'legendary', top: 8, right: 'A', bottom: 5, left: 7 },
    
    { id: 19, category: 'artifact', rarity: 'common', top: 4, right: 4, bottom: 4, left: 4 },
    { id: 20, category: 'artifact', rarity: 'common', top: 5, right: 5, bottom: 1, left: 5 },
    { id: 21, category: 'artifact', rarity: 'rare', top: 1, right: 7, bottom: 7, left: 4 },
    { id: 22, category: 'artifact', rarity: 'rare', top: 7, right: 2, bottom: 5, left: 6 },
    { id: 23, category: 'artifact', rarity: 'epic', top: 7, right: 7, bottom: 4, left: 6 },
    { id: 24, category: 'artifact', rarity: 'legendary', top: 'A', right: 3, bottom: 'A', left: 3 }
];

type CategoryFilter = 'all' | 'human' | 'animal' | 'beast' | 'artifact';

const Collection = () => {
    const { t } = useTranslation();
    const [activeFilter, setActiveFilter] = useState<CategoryFilter>('all');

    // MOCK: Simulamos que el usuario tiene desbloqueadas 
    const unlockedCardIds = [1, 2, 3, 4, 5, 6,  13, 14, 15, 16, 17, 20, 21, 22, 23, 24];

    const filteredCards = CARDS_DATABASE.filter(card => 
        activeFilter === 'all' ? true : card.category === activeFilter
    );

    // Array de filtros para renderizar los botones
    const filters: { id: CategoryFilter; labelKey: string }[] = [
        { id: 'all', labelKey: 'common.all' }, // Asegúrate de tener "all": "Todas" en tus JSON
        { id: 'human', labelKey: 'categories.human' },
        { id: 'animal', labelKey: 'categories.animal' },
        { id: 'beast', labelKey: 'categories.beast' },
        { id: 'artifact', labelKey: 'categories.artifact' },
    ];

    return (
        <DashboardLayout isCentered={false}>
            <div className="max-w-6xl mx-auto w-full animate-fade-in-up pb-20">
                
                {/* Cabecera */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-white/5 pb-6 mb-8">
                    <div>
                        <h1 className="text-4xl font-bold text-white text-center tracking-tight drop-shadow-md mb-2">
                            {t('navbar.collection')}
                        </h1>
                        <p className="text-slate-400">
                            {unlockedCardIds.length} / {CARDS_DATABASE.length} {t('common.cards_unlocked', 'Cartas desbloqueadas')}
                        </p>
                    </div>

                    {/* Botones de Filtro */}
                    <div className="flex flex-wrap gap-2 sm:gap-2 justify-center">
                        {filters.map(filter => (
                            <button
                                key={filter.id}
                                onClick={() => setActiveFilter(filter.id)}
                                className={`px-4 py-2 rounded-xl text-sm font-bold transition-all ${
                                    activeFilter === filter.id 
                                    ? 'bg-brand-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.4)]' 
                                    : 'bg-dark-800 text-slate-400 border border-white/10 hover:bg-white/5 hover:text-white'
                                }`}
                            >
                                {t(filter.labelKey)}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Cuadrícula del Álbum */}
                {/* <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6"> */}
				<div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-8 justify-items-center">
                    {filteredCards.map((card) => (
                        <GameCard 
                            key={card.id} 
                            card={card} 
                            name={t(`cards.${card.id}.name`)}
                            isUnlocked={unlockedCardIds.includes(card.id)}
                        />
                    ))}
                </div>

            </div>
        </DashboardLayout>
    );
};

export default Collection;