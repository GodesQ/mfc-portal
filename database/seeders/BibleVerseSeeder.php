<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BibleVerseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bible_verses')->insert([
            ['verse' => 'And our hope for you is firm, because we know that just as you share in our sufferings, so also you share in our comfort.', 'verse_number' => '2 Corinthians 1:7'],
            ['verse' => 'The weapons we fight with are not the weapons of the world. On the contrary, they have divine power to demolish strongholds. We demolish arguments and every pretension that sets itself up against the knowledge of God, and we take captive every thought to make it obedient to Christ.', 'verse_number' => '2 Corinthians 10:4-5'],
            ['verse' => 'Therefore, if anyone is in Christ, he is a new creation; the old has gone, the new has come!', 'verse_number' => '2 Corinthians 5:17'],
            ['verse' => 'For God did not give us a spirit of timidity, but a spirit of power, of love and of self-discipline.', 'verse_number' => '2 Timothy 1:7'],
            ['verse' => 'This same Jesus, who has been taken from you into heaven, will come back in the same way you have seen him go into heaven.', 'verse_number' => 'Acts 1:11'],
            ['verse' => 'Everyone who believes in him receives forgiveness of sins through his name.', 'verse_number' => 'Acts 10:43'],
            ['verse' => 'Believe in the Lord Jesus, and you will be saved - you and your household.', 'verse_number' => 'Acts 16:31'],
            ['verse' => 'All the days of our lives in the temple of the LORD.', 'verse_number' => null],
            ['verse' => 'You will receive an inheritance from the Lord as a reward.', 'verse_number' => 'Colossians 3:24'],
            ['verse' => 'When Christ, who is your life, appears, then you also will appear with him in glory.', 'verse_number' => 'Colossians 3:4'],
            ['verse' => 'The Lord our God is merciful and forgiving, even though we have rebelled against him.', 'verse_number' => 'Daniel 9:9'],
            ['verse' => 'He is your praise; he is your God, who performed for you those great and awesome wonders you saw with your own eyes.', 'verse_number' => 'Deuteronomy 10:21'],
            ['verse' => 'For the Lord your God is the one who goes with you to fight for you against your enemies to give you victory.', 'verse_number' => 'Deuteronomy 20:4'],
            ['verse' => 'Be strong and courageous. Do not be afraid or terrified because of them, for the Lord your God goes with you; he will never leave you nor forsake you.', 'verse_number' => 'Deuteronomy 31:6'],
            ['verse' => 'The Lord himself goes before you and will be with you; he will never leave nor forsake you. Do not be afraid; do not be discouraged.', 'verse_number' => 'Deuteronomy 31:8'],
            ['verse' => 'The eternal God is your refuge, and underneath are the everlasting arms. He will drive out your enemy before you, saying, "Destroy him!"', 'verse_number' => 'Deuteronomy 33:27'],
            ['verse' => 'If you seek the Lord your God, you will find Him if you look for Him with all your heart and with all your soul.', 'verse_number' => 'Deuteronomy 4:29'],
            ['verse' => 'Remember the Lord your God, for it is he who gives you the ability to produce wealth.', 'verse_number' => 'Deuteronomy 8:18'],
            ['verse' => 'Know then in your heart that as a man disciplines his son, so the Lord your God disciplines you.', 'verse_number' => 'Deuteronomy 8:5'],
            ['verse' => 'In him we have redemption through his blood, the forgiveness of sins, in accordance with the riches of God\'s grace.', 'verse_number' => 'Ephesians 1:7'],
            ['verse' => 'He himself is our peace.', 'verse_number' => 'Ephesians 2:14'],
            ['verse' => 'Because of his great love for us, God, who is rich in mercy, made us alive with Christ even when we were dead in transgression - it is by grace you have been saved. And God raised us up with Christ and seated us with him in the heavenly realms in Christ Jesus, in order that in the coming ages he might show the incomparable riches of his grace, expressed in his kindness to us in Christ Jesus...For we are God\'s workmanship, created in Christ Jesus to do good works, which God prepared in advance for us to do.', 'verse_number' => 'Ephesians 2:4-7,10'],
            ['verse' => 'In Him and through faith in Him we may approach God with freedom and confidence.', 'verse_number' => 'Ephesians 3:12'],
            ['verse' => 'And I pray that...together with all the saints, [you may] know the love of Christ that surpasses knowledge - that you may be filled to measure of all the fullness of God.', 'verse_number' => 'Ephesians 3:19-19'],
            ['verse' => 'For you were once darkness, but now you are light in the Lord. Live as children of light.', 'verse_number' => 'Ephesians 5:8'],
            ['verse' => 'He chose us in him before the creation of the world to be holy and blameless in his sight.', 'verse_number' => 'Ephesians 1:4'],
            ['verse' => 'Christ loved the church and gave himself up for her to make her holy, cleansing her by the washing with water through the word, and to present her to himself as a radiant church, without stain or wrinkle or any other blemish.', 'verse_number' => 'Ephesians 5:25-27'],
            ['verse' => 'Do not be afraid. Stand firm and you will see the deliverance the Lord will bring you.', 'verse_number' => 'Exodus 14:13'],
            ['verse' => 'In your unfailing love you will lead the people you have redeemed. In your strength you will guide them to your holy dwelling.', 'verse_number' => 'Exodus 15:13'],
            ['verse' => 'Now if you obey me fully and keep my covenant, then out of all nations you will be my treasured possession. Although the whole earth is mine, you will be for me a kingdom of priests and a holy nation.', 'verse_number' => 'Exodus 19:5-6'],
            ['verse' => 'My Presence will go with you, and I will give you rest.', 'verse_number' => 'Exodus 33:14'],
            ['verse' => 'I will drive out nations before you and enlarge your territory.', 'verse_number' => 'Exodus 34:24'],
            ['verse' => 'I will send down showers in season; there will be showers of blessing.', 'verse_number' => 'Ezekiel 34:26'],
            ['verse' => '"You my sheep, the sheep of my pasture, are people, and I am your God," declares the Sovereign Lord.', 'verse_number' => 'Ezekiel 34:31'],
            ['verse' => 'I will give you a new heart and put a new spirit in you.', 'verse_number' => 'Ezekiel 36:26'],
            ['verse' => 'I am concerned for you and will look on you with favor.', 'verse_number' => 'Ezekiel 36:9'],
            ['verse' => 'You are all sons of God through faith in Christ Jesus.', 'verse_number' => 'Galatians 3:26'],
            ['verse' => 'I will bless those who bless you, and whoever curses you I will curse; and all peoples on earth will be blessed through you.', 'verse_number' => 'Genesis 12:3'],
            ['verse' => 'The LORD appeared to Abram and said, “To your offspring I will give this land.”', 'verse_number' => 'Genesis 12:7'],
            ['verse' => 'I will establish my covenant as an everlasting covenant between me and you and your descendants after you for the generations to come, to be your God and the God of your descendants after you.', 'verse_number' => 'Genesis 17:7'],
            ['verse' => 'I am with you and will watch over you wherever you go, and I will bring you back to this land. I will not leave you until I have done what I have promised.', 'verse_number' => 'Genesis 28:15'],
            ['verse' => 'The Sovereign Lord is my strength; he makes my feet like the feet of a deer, he enables me to go on the heights.', 'verse_number' => 'Habakkuk 3:19'],
            ['verse' => '"Be strong, all you people of the land," declares the Lord, "and work. For I am with you."', 'verse_number' => 'Haggai 2:4'],
            ['verse' => 'You need to persevere so that when you have done the will of God, you will receive what he has promised.', 'verse_number' => 'Hebrews 10:36'],
            ['verse' => 'God disciplines us for our good, that we may share in his holiness.', 'verse_number' => 'Hebrews 12:10'],
            ['verse' => '"Never will I leave you; never will I forsake you."', 'verse_number' => 'Hebrews 13:5'],
            ['verse' => 'The Lord is my helper; I will not be afraid. What can man do to me?', 'verse_number' => 'Hebrews 13:6'],
            ['verse' => 'God...will not forget your work and the love you have shown him as you have helped his people and continue to help them.', 'verse_number' => 'Hebrews 6:10'],
        ]);
    }
}
