import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import DashboardLayout from '../components/layouts/DashboardLayout';
import GameCard, { type CardData } from '../components/ui/GameCard';
import LoadingState from '../components/ui/LoadingState';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';
import i18n from '../i18n';

type CategoryFilter = 'all' | 'human' | 'animal' | 'beast' | 'artifact';

const Collection = () => {
	const { t } = useTranslation();
	const { user } = useAuth();
	const [activeFilter, setActiveFilter] = useState<CategoryFilter>('all');

	/* Estados para gestionar TODAS las cartas y las que el usuario tiene desbloqueadas */
	const [allCardsDB, setAllCardsDB] = useState<CardData[]>([]);
	const [unlockedCardIds, setUnlockedCardIds] = useState<number[]>([]);
	const [isLoading, setIsLoading] = useState(true);

	/* TEMPORAL: Función de apoyo por si la BBDD aún no tiene la columna 'category' */
	const getCategoryFallback = (id: number): CategoryFilter => {
		if (id <= 6) return 'human';
		if (id <= 12) return 'animal';
		if (id <= 18) return 'beast';
		return 'artifact';
	};

	/* useEffect para obtener los datos al cargar la página */
	useEffect(() => {
        const fetchCollectionData = async () => {
            if (!user) return;
            setIsLoading(true);

            try {
                // Obtenemos el idioma actual exacto ('es', 'en', 'ca')
                const currentLang = i18n.language?.split('-')[0] || 'es';

                /* Pasamos el idioma al backend para que nos devuelva los textos correctos */
                const [catalogData, userCardsData] = await Promise.all([
                    userService.getAllCards(currentLang),
                    userService.getCards(currentLang)
                ]);

                // Normalizamos los datos que vienen del backend
                const formattedCatalog: CardData[] = catalogData.map((card: any) => {
                    let mappedRarity = String(card.rarity).toLowerCase();
                    if (mappedRarity.includes('golden')) mappedRarity = 'legendary';

                    return {
                        id: Number(card.id),
                        category: card.category || getCategoryFallback(Number(card.id)),
                        rarity: mappedRarity as 'common' | 'rare' | 'epic' | 'legendary',
                        top: card.stats?.top === 10 ? 'A' : card.stats?.top,
                        right: card.stats?.right === 10 ? 'A' : card.stats?.right,
                        bottom: card.stats?.bottom === 10 ? 'A' : card.stats?.bottom,
                        left: card.stats?.left === 10 ? 'A' : card.stats?.left,
                        
                        // Guardamos el nombre y la descripción tal cual nos los manda el backend traducidos
                        name: card.name, 
                        description: card.description || '',
                    };
                });

                const unlockedIds = userCardsData.map((card: any) => Number(card.id || card.card_id || card.pivot?.card_id));

                setAllCardsDB(formattedCatalog);
                setUnlockedCardIds(unlockedIds);

            } catch (error) {
                console.error("Error cargando la colección desde la BD:", error);
                setAllCardsDB([]);
                setUnlockedCardIds([]);
            } finally {
                setIsLoading(false);
            }
        };

        fetchCollectionData();
    }, [user, i18n.language]);

	// Filtramos sobre el estado `allCardsDB` que contiene todas las cartas
	const filteredCards = allCardsDB.filter(card =>
		activeFilter === 'all' ? true : card.category === activeFilter
	);

	// Array de filtros para renderizar los botones
	const filters: { id: CategoryFilter; labelKey: string }[] = [
		{ id: 'all', labelKey: 'common.all' },
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
						<h1 className="text-4xl font-bold text-white text-center md:text-start tracking-tight drop-shadow-md mb-2">
							{t('navbar.collection')}
						</h1>
						<p className="text-slate-400">
							{unlockedCardIds.length} / {allCardsDB.length || 24} {t('common.cards_unlocked', 'Cartas desbloqueadas')}
						</p>
					</div>

					{/* Botones de Filtro */}
					<div className="flex flex-wrap gap-2 sm:gap-2 justify-center">
						{filters.map(filter => (
							<button
								key={filter.id}
								onClick={() => setActiveFilter(filter.id)}
								className={`px-4 py-2 rounded-xl text-sm font-bold transition-all ${activeFilter === filter.id
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
				{isLoading ? (
					<LoadingState message={t('common.loading')} />
				) : (
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
				)}
			</div>
		</DashboardLayout>
	);
};

export default Collection;