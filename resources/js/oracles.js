const swordAndSorcery = {
    name: "Sword and Sorcery",
    description: "The Swords & Sorcery Oracle creates histories of bold fantasy. Dragons’ treasure hoards, feuding kingdoms, woods that walk, dead gods and runes of power. Druids, sages, princes and thieves. Valor, heroism and terrible deeds. Fell swords, bright spears and terrible oaths that bind your bloodline to ruin.",
    trends: [
        "Rise of",
        "Decline of",
        "Creation of",
        "Destruction of",
        "Corruption of",
        "Stagnation of",
    ],
    impacts: [
        "strengthens",
        "rebuilds",
        "creates",
        "destroys",
        "corrupts",
        "divides",
    ],
    elements: [
        ["Three Swords of Power", "Magic Forge", "Empire", "Magic", "Bloodline", "Mercenaries", "Savage Wilderness", "Cult of a forgotten God"],
        ["Rune-Spear", "Star-Metal", "Kingdom", "a religion", "Exiles", "Warlords", "Sacred Mountain", "Nature Spirits"],
        ["Staff of Lore", "Prophecy", "Sister-Cities", "Gods", "Race", "Order of Knights", "Peaceful Shire", "Titans"],
        ["Cursed Crown", "Oath", "Clans", "Demons", "Eldritch Folk", "Assassins' Guild", "Hidden City", "Monuments of Kings"],
        ["Sacred Skull", "Plague & Famine", "Secret Society", "Curse", "Dragons", "Sect of Priests", "Ruins buried beneath the sands", "Crossroad of Nations"],
        ["Treasure Horde", "Tempests, Floods or Quakes", "Runes of Power", "Feud", "Conquering Horde", "Circle of Wizards", "Trade Route", "Great Wall"],
    ],
};

const toTheStars = {
    name: "To the Stars",
    description: "The To the Stars Oracle generates interstellar science fiction, packed with warp-gates, alien civilizations, galactic war and humanity’s never-ending struggle to adapt to new and strange environments.",
    trends: [
        "Creation of",
        "Decline of",
        "Discovery of",
        "Destruction of",
        "Corruption of",
        "Rejection of",
    ],
    impacts: [
        "strengthens",
        "rebuilds",
        "creates",
        "destroy",
        "corrupts",
        "divides",
    ],
    elements: [
        ["Star", "Spores", "Space Travel", "Artificial Intelligence", "Primitive Civilization", "Habitable World", "Cosmic Weapons", "Drug"],
        ["Dead World", "Unintelligent Lifeforms", "Vital Energy Source", "Synthetic People", "Splinter Race", "Corporation", "Armada", "Xenophobia"],
        ["Black Hole", "Expansionism", "Medical Technology", "Artificial Life", "Human Augmentations", "Galactic Patrol", "An Institute", "Human Exceptionalism"],
        ["Warp Gates", "Isolationism", "Superior Alien Civilization", "Alien Artifacts", "Mutations", "a Religion", "Space Pirates", "Social Equality"],
        ["Plague", "Imperialism", "Inferior Alien Civilization", "Hostile Ecosystem", "Empire", "Secret Society", "Artificial World", "Fear of Change"],
        ["Space Monsters", "Spirit of Exploration", "Rival Alien Civilization", "Psi Talents", "Federation", "Political Party", "Sentient Star or Planet", "Naturalism"],
    ],
};

const cradleOfCivilization = {
    name: "Cradle of Civilization",
    description: "If you want to explore the early collisions of society, invention and culture, the Cradle of Civilization Oracle is for you. Tame fire. Invent the wheel. Cultivate the land. Write the first laws. Erect monuments that defy death itself.",
    trends: [
        "Invention of",
        "Advances of",
        "Failure of",
        "Desire for",
        "Abandoning",
        "Spread of",
    ],
    impacts: [
        "makes obsolete",
        "undermines",
        "impedes",
        "transforms",
        "accelerates",
        "creates",
    ],
    elements: [
        ["Astrology", "Burial Customs", "Priests", "Democracy", "Chariots", "Law", "Foreign Crop", "Division of Labor"],
        ["Calendar", "Architecture", "Chiefs or Rulers", "Tyranny", "Wheel", "Cities", "Fertile Land", "Cartography"],
        ["Graven Idols", "Roads", "Hunters", "Slavery", "Religion", "Currency", "Mining", "Armor"],
        ["Philosophy", "Taxes", "Farmers", "Swords", "Superstition", "Pottery", "Hospitality Customs", "Games"],
        ["Dance", "Irrigation", "Craftworkers", "Medicine", "Marriage", "Fire", "Domesticated Animals", "Tournaments"],
        ["Singing & Storytelling", "Gunpowder", "Monarchy", "Trade", "Writing", "Metallurgy", "Seamanship", "Sanitation"]
    ],
};

export default [
    swordAndSorcery,
    toTheStars,
    cradleOfCivilization,
];
