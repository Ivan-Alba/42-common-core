import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import DashboardLayout from '../components/layouts/DashboardLayout';
import GameCard from '../components/ui/GameCard';
import LoadingState from '../components/ui/LoadingState';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';
import i18n from '../i18n';
import type { CardData } from '../models/CardData';
import { FaChevronDown } from "react-icons/fa";

const Collection = () => {
	const { t } = useTranslation();
	const { user } = useAuth();
	const [activeFilter, setActiveFilter] = useState<string>('all');
	const [allCardsDB, setAllCardsDB] = useState<CardData[]>([]);
	const [unlockedCardIds, setUnlockedCardIds] = useState<number[]>([]);
	const [categories, setCategories] = useState<string[]>([]);
	const [cardTheme, setCardTheme] = useState<'blue' | 'red'>('blue');
	const [isLoading, setIsLoading] = useState(true);

	useEffect(() => {
		const fetchCollectionData = async () => {
			if (!user)
				return;
			setIsLoading(true);

			try {
				const currentLang = i18n.language?.split('-')[0] || 'es';

				const [catalogData, userCardsData] = await Promise.all([
					userService.getAllCards(currentLang),
					userService.getCards(currentLang)
				]);

				/* Make a set to track unique categories names without duplicates. */
				const uniqueCategories = new Set<string>();

				const formattedCatalog: CardData[] = catalogData.map((card: any) => {
					let mappedRarity = String(card.rarity).toLowerCase();
					if (mappedRarity.includes('golden')) mappedRarity = 'legendary';

					const category = String(card.category).toLowerCase();
					/* Add the category to the set */
					uniqueCategories.add(category);

					return {
						id: Number(card.id),
						name: card.name || '',
						description: card.description || '',
						category: card.category,
						rarity: mappedRarity as CardData['rarity'],
						stats: {
							top: card.stats?.top === 10 ? 'A' : card.stats?.top,
							right: card.stats?.right === 10 ? 'A' : card.stats?.right,
							bottom: card.stats?.bottom === 10 ? 'A' : card.stats?.bottom,
							left: card.stats?.left === 10 ? 'A' : card.stats?.left
						}
					};
				});

				const unlockedIds = userCardsData.map((card: any) => Number(card.id || card.card_id || card.pivot?.card_id));

				setAllCardsDB(formattedCatalog);
				setUnlockedCardIds(unlockedIds);

				/* Save the unique categories to state, ensuring we only have distinct category names for filtering. */
				setCategories(Array.from(uniqueCategories));

			} catch (error) {
				console.error("Error: ", error);
				setAllCardsDB([]);
				setUnlockedCardIds([]);
				setCategories([]);
			} finally {
				setIsLoading(false);
			}
		};

		fetchCollectionData();
	}, [user, i18n.language]);

	const filteredCards = allCardsDB.filter(card =>
		activeFilter === 'all' ? true : card.category === activeFilter
	);

	return (
		<DashboardLayout isCentered={false}>
			<div className="max-w-6xl mx-auto w-full animate-fade-in-up pb-20 relative">

				{/* Sticky Panel Control */}
				<div className="sticky top-24 z-40 bg-dark-900/95 backdrop-blur-md border-b border-white/5 shadow-[0_10px_30px_rgba(0,0,0,0.5)] pt-3 pb-3 sm:pt-6 sm:pb-6 mb-4 sm:mb-8 -mx-6 px-6 sm:mx-0 sm:px-6 sm:rounded-b-2xl">

					<div className="flex flex-col gap-3 sm:gap-5">

						<div className="flex justify-between items-center w-full">

							{/* Collection Header */}
							<div className="flex flex-col sm:items-baseline gap-0 sm:gap-2">
								<h1 className="text-2xl sm:text-4xl font-bold text-white tracking-tight drop-shadow-md leading-none">
									{t('navbar.collection')}
								</h1>
								{/* Minimal on mobile, complete on desktop */}
								<p className="text-slate-500 sm:text-slate-400 text-[10px] sm:text-sm font-medium mt-1 sm:mt-0">
									{unlockedCardIds.length} / {allCardsDB.length || 24} <span className="hidden sm:inline">{t('collection.cards_unlocked')}</span>
								</p>
							</div>

							{/* Toggle Blue/Red */}
							<div className="flex items-center gap-1 sm:gap-2 bg-dark-900 p-1 sm:p-1.5 rounded-xl sm:rounded-2xl border border-white/5 shadow-inner">
								<button
									onClick={() => setCardTheme('blue')}
									className={`px-3 sm:px-5 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-sm font-bold transition-all duration-300 ${cardTheme === 'blue' ? 'bg-brand-500 text-white shadow-[0_0_10px_rgba(59,130,246,0.5)]' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
								>
									{t('common.player', 'Jugador')}
								</button>
								<button
									onClick={() => setCardTheme('red')}
									className={`px-3 sm:px-5 py-1.5 sm:py-2 rounded-lg text-[10px] sm:text-sm font-bold transition-all duration-300 ${cardTheme === 'red' ? 'bg-danger text-white shadow-[0_0_10px_rgba(239,68,68,0.5)]' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
								>
									{t('common.opponent', 'Oponent')}
								</button>
							</div>

						</div>

						{/* Filter search */}
						<div className="w-full flex justify-end">

							{/* Mobile View */}
							<div className="relative md:hidden w-full">
								<select
									value={activeFilter}
									onChange={(e) => setActiveFilter(e.target.value)}
									className="w-full bg-dark-800 border border-white/10 text-white text-xs font-semibold rounded-xl pl-4 pr-10 py-3 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all appearance-none shadow-sm cursor-pointer"
									style={{ WebkitAppearance: 'none' }}
								>
									<option value="all">{t('common.all')}</option>
									{categories.map(category => (
										<option key={category} value={category}>
											{t(`categories.${category}`, { defaultValue: category })}
										</option>
									))}
								</select>
								{/* Down arrow icon on right position */}
								<div className="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
									<FaChevronDown size={14} />
								</div>
							</div>

							{/* Desktop View */}
							<div className="hidden md:flex flex-wrap gap-2 justify-end">
								<button
									key="all"
									onClick={() => setActiveFilter("all")}
									className={`px-4 py-2 rounded-xl text-sm font-bold transition-all uppercase ${activeFilter === "all"
										? 'bg-brand-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.4)]'
										: 'bg-dark-800 text-slate-400 border border-white/10 hover:bg-white/5 hover:text-white'
										}`}
								>
									{t('common.all', 'Totes')}
								</button>

								{categories.map(category => (
									<button
										key={category}
										onClick={() => setActiveFilter(category)}
										className={`px-4 py-2 rounded-xl text-sm font-bold transition-all uppercase ${activeFilter === category
											? 'bg-brand-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.4)]'
											: 'bg-dark-800 text-slate-400 border border-white/10 hover:bg-white/5 hover:text-white'
											}`}
									>
										{t(`categories.${category}`, { defaultValue: category })}
									</button>
								))}
							</div>

						</div>
					</div>
				</div>

				{/* Cards view */}
				{isLoading ? (
					<LoadingState message={t('common.loading')} />
				) : (
					<div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 md:gap-8 justify-items-center relative z-10">
						{filteredCards.map((card) => (
							<GameCard
								key={card.id}
								card={card}
								isUnlocked={unlockedCardIds.includes(card.id)}
								team={cardTheme}
							/>
						))}
					</div>
				)}
			</div>
		</DashboardLayout>
	);
};

export default Collection;