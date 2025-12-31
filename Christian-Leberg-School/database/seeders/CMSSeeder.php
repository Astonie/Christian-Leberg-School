<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use App\Models\Event;
use App\Models\Album;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@christianleberg.com'],
            [
                'name' => 'CMS Administrator',
                'password' => bcrypt('password'),
            ]
        );

        // Create Settings
        $this->createSettings();

        // Create Categories
        $categories = $this->createCategories();

        // Create Tags
        $tags = $this->createTags();

        // Create Pages
        $this->createPages($admin);

        // Create Posts
        $this->createPosts($admin, $categories, $tags);

        // Create Events
        $this->createEvents($admin, $tags);

        // Create Albums
        $this->createAlbums();

        // Create Menus
        $this->createMenus();

        $this->command->info('CMS seeded successfully!');
    }

    private function createSettings()
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'School Portal', 'type' => 'text', 'group' => 'general', 'label' => 'School Name', 'description' => 'The name of your school'],
            ['key' => 'site_tagline', 'value' => 'Empowering the next generation through quality education', 'type' => 'text', 'group' => 'general', 'label' => 'Site Tagline', 'description' => 'A short tagline for your school'],
            ['key' => 'site_description', 'value' => 'Excellence in Education - Building futures through quality education', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'A brief description of your school'],
            ['key' => 'about_history', 'value' => 'Our school has a rich history of providing quality education to students. We are committed to excellence in teaching and learning, nurturing the whole child and preparing students for success in their future endeavors.', 'type' => 'textarea', 'group' => 'general', 'label' => 'About/History Text', 'description' => 'History text shown on homepage'],
            ['key' => 'contact_email', 'value' => 'info@school.edu', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '+000 000 000 000', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone'],
            ['key' => 'contact_address', 'value' => 'School Address Here', 'type' => 'textarea', 'group' => 'general', 'label' => 'Contact Address'],
            
            // Hero Section
            ['key' => 'hero_badge_text', 'value' => 'Admissions Open 2025', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Badge Text', 'description' => 'Text shown in the badge on hero section'],
            ['key' => 'hero_image_1', 'value' => 'images/clss.jpg', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Image 1', 'description' => 'Main campus/school image (top-left)'],
            ['key' => 'hero_image_2', 'value' => 'images/clss-students.jpg', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Image 2', 'description' => 'Students image (bottom-left)'],
            ['key' => 'hero_image_3', 'value' => 'images/clss-class.jpg', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Image 3', 'description' => 'Classroom image (top-right)'],
            ['key' => 'hero_image_4', 'value' => 'images/clss-view.jpg', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Image 4', 'description' => 'Campus view image (bottom-right)'],
            
            // Features Section
            ['key' => 'features_title', 'value' => 'Why Choose Us', 'type' => 'text', 'group' => 'homepage', 'label' => 'Features Section Title'],
            ['key' => 'features_subtitle', 'value' => 'We provide a comprehensive education that prepares students for success in an ever-changing world', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Features Section Subtitle'],
            
            ['key' => 'feature_1_title', 'value' => 'Quality Education', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 1 Title'],
            ['key' => 'feature_1_description', 'value' => 'Modern curriculum designed to develop critical thinking and practical skills', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Feature 1 Description'],
            ['key' => 'feature_1_icon', 'value' => 'book', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 1 Icon', 'description' => 'Icon name: book, users, shield, lightning'],
            ['key' => 'feature_1_color', 'value' => 'blue', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 1 Color', 'description' => 'Color: blue, purple, green, orange'],
            
            ['key' => 'feature_2_title', 'value' => 'Experienced Staff', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 2 Title'],
            ['key' => 'feature_2_description', 'value' => 'Dedicated and qualified teachers committed to student success', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Feature 2 Description'],
            ['key' => 'feature_2_icon', 'value' => 'users', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 2 Icon'],
            ['key' => 'feature_2_color', 'value' => 'purple', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 2 Color'],
            
            ['key' => 'feature_3_title', 'value' => 'Safe Environment', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 3 Title'],
            ['key' => 'feature_3_description', 'value' => 'Secure campus with comprehensive safety measures and pastoral care', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Feature 3 Description'],
            ['key' => 'feature_3_icon', 'value' => 'shield', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 3 Icon'],
            ['key' => 'feature_3_color', 'value' => 'green', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 3 Color'],
            
            ['key' => 'feature_4_title', 'value' => 'Modern Facilities', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 4 Title'],
            ['key' => 'feature_4_description', 'value' => 'State-of-the-art classrooms, labs, and sports facilities', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Feature 4 Description'],
            ['key' => 'feature_4_icon', 'value' => 'lightning', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 4 Icon'],
            ['key' => 'feature_4_color', 'value' => 'orange', 'type' => 'text', 'group' => 'homepage', 'label' => 'Feature 4 Color'],
            
            // Stats Section
            ['key' => 'stats_title', 'value' => 'Our Achievements', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stats Section Title'],
            ['key' => 'stats_subtitle', 'value' => 'Proud of our accomplishments and continued commitment to excellence', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Stats Section Subtitle'],
            
            ['key' => 'stat_1_value', 'value' => '1000+', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 1 Value'],
            ['key' => 'stat_1_label', 'value' => 'Students', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 1 Label'],
            
            ['key' => 'stat_2_value', 'value' => '50+', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 2 Value'],
            ['key' => 'stat_2_label', 'value' => 'Teachers', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 2 Label'],
            
            ['key' => 'stat_3_value', 'value' => '20+', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 3 Value'],
            ['key' => 'stat_3_label', 'value' => 'Years', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 3 Label'],
            
            ['key' => 'stat_4_value', 'value' => '95%', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 4 Value'],
            ['key' => 'stat_4_label', 'value' => 'Success Rate', 'type' => 'text', 'group' => 'homepage', 'label' => 'Stat 4 Label'],
            
            // CTA Section
            ['key' => 'cta_title', 'value' => 'Ready to Join Us?', 'type' => 'text', 'group' => 'homepage', 'label' => 'CTA Section Title'],
            ['key' => 'cta_subtitle', 'value' => 'Discover how our school can help your child excel and reach their full potential', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'CTA Section Subtitle'],
            ['key' => 'cta_button_1_text', 'value' => 'Apply Now', 'type' => 'text', 'group' => 'homepage', 'label' => 'CTA Button 1 Text'],
            ['key' => 'cta_button_1_link', 'value' => '/page/admissions', 'type' => 'text', 'group' => 'homepage', 'label' => 'CTA Button 1 Link'],
            ['key' => 'cta_button_2_text', 'value' => 'Contact Us', 'type' => 'text', 'group' => 'homepage', 'label' => 'CTA Button 2 Text'],
            ['key' => 'cta_button_2_link', 'value' => '/page/contact', 'type' => 'text', 'group' => 'homepage', 'label' => 'CTA Button 2 Link'],
            
            // SEO & Social
            ['key' => 'site_keywords', 'value' => 'school, education, learning, secondary school', 'type' => 'text', 'group' => 'seo', 'label' => 'SEO Keywords'],
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Twitter URL'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function createCategories()
    {
        $categories = [
            ['name' => 'School News', 'description' => 'Latest updates and news from the school'],
            ['name' => 'Academic Excellence', 'description' => 'Academic achievements and programs'],
            ['name' => 'Sports & Activities', 'description' => 'Sports events and extracurricular activities'],
            ['name' => 'Community Engagement', 'description' => 'Community events and partnerships'],
            ['name' => 'Student Life', 'description' => 'Student experiences and activities'],
        ];

        $created = [];
        foreach ($categories as $index => $category) {
            $created[] = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'order' => $index,
                ]
            );
        }

        return collect($created);
    }

    private function createTags()
    {
        $tagNames = ['Academics', 'Sports', 'Events', 'Community', 'Achievement', 'Innovation', 'Technology', 'Arts', 'Science', 'Mathematics'];
        
        $tags = [];
        foreach ($tagNames as $name) {
            $tags[] = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        return collect($tags);
    }

    private function createPages($admin)
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'excerpt' => 'Learn more about our school and mission',
                'content' => "Our school has been a beacon of educational excellence. Our mission is to provide quality education that nurtures the whole child - academically, socially, emotionally, and spiritually.\n\nWe believe in creating a supportive learning environment where every student can thrive and reach their full potential. Our dedicated faculty and staff are committed to fostering a love of learning and preparing students for success in an ever-changing world.",
                'status' => 'published',
                'template' => 'default',
                'order' => 1,
            ],
            [
                'title' => 'Admissions',
                'slug' => 'admissions',
                'excerpt' => 'Join our community of learners',
                'content' => "We welcome applications from families who share our commitment to educational excellence and holistic development.\n\nAdmission Process:\n1. Submit online application\n2. Schedule campus visit\n3. Student assessment\n4. Parent interview\n5. Admission decision\n\nFor more information, please contact our admissions office.",
                'status' => 'published',
                'template' => 'default',
                'order' => 2,
            ],
            [
                'title' => 'Academics',
                'slug' => 'academics',
                'excerpt' => 'Excellence in education',
                'content' => "Our comprehensive academic program combines rigorous coursework with innovative teaching methods to prepare students for success.\n\nWe offer:\n- Strong foundation in core subjects\n- Advanced placement courses\n- Technology-integrated learning\n- Personalized learning paths\n- Regular assessments and feedback\n\nOur curriculum is designed to challenge students while providing the support they need to excel.",
                'status' => 'published',
                'template' => 'default',
                'order' => 3,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'excerpt' => 'Get in touch with us',
                'content' => "School Contact Information\n\nPhone: Contact Number Here\nEmail: info@school.edu\n\nOffice Hours:\nMonday - Friday: 8:00 AM - 5:00 PM\nSaturday: 9:00 AM - 1:00 PM\nSunday: Closed",
                'status' => 'published',
                'template' => 'default',
                'order' => 4,
            ],
        ];

        foreach ($pages as $pageData) {
            $pageData['created_by'] = $admin->id;
            $pageData['published_at'] = now();
            
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }

    private function createPosts($admin, $categories, $tags)
    {
        $posts = [
            [
                'title' => 'Welcome to the New Academic Year!',
                'excerpt' => 'We are excited to begin another year of learning and growth together.',
                'content' => "Dear Students, Parents, and Staff,\n\nWe are thrilled to welcome everyone back for another exciting academic year! This year promises to be filled with incredible learning opportunities, innovative programs, and memorable experiences.\n\nOur dedicated faculty has been preparing engaging curricula and activities to inspire and challenge our students. We look forward to partnering with families to ensure every child reaches their full potential.\n\nLet's make this year our best one yet!\n\nBest regards,\nThe Administration",
                'featured' => true,
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Science Fair 2024: A Huge Success!',
                'excerpt' => 'Students showcased amazing projects at our annual science fair.',
                'content' => "Our annual Science Fair was a tremendous success! Students from all grades presented innovative projects demonstrating their creativity and scientific knowledge.\n\nThe judges were impressed by the quality and depth of the research presented. Congratulations to all participants, and special recognition to our award winners!\n\nThank you to the parents, teachers, and volunteers who made this event possible.",
                'featured' => true,
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'New Computer Lab Opening',
                'excerpt' => 'State-of-the-art technology facility now available for students.',
                'content' => "We are proud to announce the opening of our new state-of-the-art computer lab! Equipped with the latest technology, this facility will enhance our technology education program and provide students with hands-on experience with modern computing tools.\n\nThe lab features:\n- 30 high-performance computers\n- Interactive whiteboard\n- 3D printing capabilities\n- Robotics equipment\n- Collaborative learning spaces\n\nWe invite all families to visit during our upcoming open house.",
                'featured' => false,
                'status' => 'published',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Sports Day Highlights',
                'excerpt' => 'An action-packed day of athletic competition and school spirit.',
                'content' => "Sports Day 2024 was filled with excitement, teamwork, and outstanding athletic performances! Students competed in various track and field events, showcasing their talents and sportsmanship.\n\nHighlights included:\n- Record-breaking performances in several events\n- Amazing team spirit and support\n- Parent participation in fun races\n- Award ceremonies celebrating all participants\n\nThank you to everyone who made this day special!",
                'featured' => false,
                'status' => 'published',
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Parent-Teacher Conference Schedule',
                'excerpt' => 'Mark your calendars for upcoming parent-teacher meetings.',
                'content' => "Parent-Teacher conferences are scheduled for next month. These meetings provide an excellent opportunity to discuss your child's progress, strengths, and areas for growth.\n\nSchedule:\n- Primary School: November 10-11\n- Middle School: November 12-13\n- High School: November 14-15\n\nPlease contact your child's homeroom teacher to book your preferred time slot.",
                'featured' => false,
                'status' => 'published',
                'published_at' => now()->subDays(25),
            ],
        ];

        foreach ($posts as $index => $postData) {
            $postData['author_id'] = $admin->id;
            $postData['category_id'] = $categories->random()->id;
            $slug = Str::slug($postData['title']);
            $postData['slug'] = $slug;
            $postData['views_count'] = rand(50, 500);
            
            $post = Post::updateOrCreate(
                ['slug' => $slug],
                $postData
            );
            
            // Attach random tags
            $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));
        }
    }

    private function createEvents($admin, $tags)
    {
        $events = [
            [
                'title' => 'Annual School Concert',
                'description' => 'Join us for an evening of music and performances by our talented students',
                'content' => "Our Annual School Concert showcases the musical talents of our students across all grades. From choir performances to instrumental solos and band ensembles, this event celebrates the arts in our school community.\n\nTickets are available at the school office or online. Proceeds support our music program.",
                'location' => 'School Auditorium',
                'venue' => 'Main Campus',
                'start_date' => now()->addDays(15)->setTime(18, 0),
                'end_date' => now()->addDays(15)->setTime(20, 0),
                'status' => 'published',
                'featured' => true,
                'registration_link' => 'https://example.com/register',
                'contact_email' => 'events@school.edu',
            ],
            [
                'title' => 'Open House for Prospective Families',
                'description' => 'Tour our campus and meet our faculty',
                'content' => "Interested in joining our school community? Join us for an Open House where you can:\n\n- Tour our facilities\n- Meet teachers and administrators\n- Learn about our curriculum and programs\n- Ask questions about admissions\n- Experience our school culture\n\nRSVP required. Please register online or call our admissions office.",
                'location' => 'Main Campus',
                'venue' => 'Welcome Center',
                'start_date' => now()->addDays(7)->setTime(10, 0),
                'end_date' => now()->addDays(7)->setTime(13, 0),
                'status' => 'published',
                'featured' => true,
                'registration_link' => 'https://example.com/openhouse',
            ],
            [
                'title' => 'Inter-School Mathematics Competition',
                'description' => 'Students compete in challenging math problems',
                'content' => "Our school is hosting the Regional Mathematics Competition, bringing together talented math students from schools across the region.\n\nCategories:\n- Junior Division (Grades 6-8)\n- Senior Division (Grades 9-12)\n\nSpectators welcome! Come cheer on our students.",
                'location' => 'School Gymnasium',
                'venue' => 'Main Campus',
                'start_date' => now()->addDays(30)->setTime(9, 0),
                'end_date' => now()->addDays(30)->setTime(15, 0),
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Science Exhibition Week',
                'description' => 'A week-long celebration of science and discovery',
                'content' => "Science Exhibition Week features hands-on experiments, guest speakers, science demonstrations, and student project displays.\n\nDaily Schedule:\n- Monday: Physics demonstrations\n- Tuesday: Chemistry experiments\n- Wednesday: Biology exploration\n- Thursday: Environmental science\n- Friday: Student presentations and awards\n\nAll events are open to students and families.",
                'location' => 'Science Building',
                'venue' => 'Main Campus',
                'start_date' => now()->addDays(45)->setTime(8, 0),
                'end_date' => now()->addDays(49)->setTime(16, 0),
                'all_day' => true,
                'status' => 'published',
                'featured' => false,
            ],
        ];

        foreach ($events as $eventData) {
            $eventData['created_by'] = $admin->id;
            $slug = Str::slug($eventData['title']);
            $eventData['slug'] = $slug;
            
            $event = Event::updateOrCreate(
                ['slug' => $slug],
                $eventData
            );
            
            // Attach random tags
            $event->tags()->sync($tags->random(rand(1, 3))->pluck('id'));
        }
    }

    private function createAlbums()
    {
        $albums = [
            ['name' => 'School Events', 'description' => 'Photos from various school events and activities'],
            ['name' => 'Campus Life', 'description' => 'Daily life at school'],
            ['name' => 'Sports & Athletics', 'description' => 'Sports teams and athletic events'],
            ['name' => 'Academic Excellence', 'description' => 'Classroom activities and academic achievements'],
        ];

        foreach ($albums as $index => $albumData) {
            Album::updateOrCreate(
                ['slug' => Str::slug($albumData['name'])],
                [
                    'name' => $albumData['name'],
                    'description' => $albumData['description'],
                    'status' => 'published',
                    'order' => $index,
                ]
            );
        }
    }

    private function createMenus()
    {
        // Header Menu
        $headerMenu = Menu::updateOrCreate(
            ['slug' => 'header-menu'],
            [
                'name' => 'Header Menu',
                'location' => 'header',
                'active' => true,
            ]
        );

        // Delete existing items to avoid duplicates
        $headerMenu->allItems()->delete();

        $headerItems = [
            ['title' => 'Home', 'url' => '/', 'order' => 1],
            ['title' => 'About', 'url' => '/page/about', 'order' => 2],
            ['title' => 'Admissions', 'url' => '/page/admissions', 'order' => 3],
            ['title' => 'News', 'url' => '/blog', 'order' => 4],
            ['title' => 'Events', 'url' => '/events', 'order' => 5],
            ['title' => 'Contact', 'url' => '/page/contact', 'order' => 6],
        ];

        foreach ($headerItems as $item) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'order' => $item['order'],
                'active' => true,
            ]);
        }

        // Footer Menu
        $footerMenu = Menu::updateOrCreate(
            ['slug' => 'footer-menu'],
            [
                'name' => 'Footer Menu',
                'location' => 'footer',
                'active' => true,
            ]
        );

        // Delete existing items to avoid duplicates
        $footerMenu->allItems()->delete();

        $footerItems = [
            ['title' => 'Privacy Policy', 'url' => '/page/privacy', 'order' => 1],
            ['title' => 'Terms of Service', 'url' => '/page/terms', 'order' => 2],
            ['title' => 'Admissions', 'url' => '/page/admissions', 'order' => 3],
        ];

        foreach ($footerItems as $item) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'order' => $item['order'],
                'active' => true,
            ]);
        }
    }
}
