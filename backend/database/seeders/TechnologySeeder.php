<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Technology;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technologies = [
            // Frontend
            [
                'name' => 'React',
                'slug' => 'react',
                'icon' => 'fab fa-react',
                'color' => '#61DAFB',
                'category' => 'frontend',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vuejs',
                'icon' => 'fab fa-vuejs',
                'color' => '#4FC08D',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'icon' => 'fab fa-js-square',
                'color' => '#F7DF1E',
                'category' => 'frontend',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'TypeScript',
                'slug' => 'typescript',
                'icon' => 'fas fa-code',
                'color' => '#3178C6',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 4
            ],
            [
                'name' => 'Tailwind CSS',
                'slug' => 'tailwindcss',
                'icon' => 'fas fa-palette',
                'color' => '#06B6D4',
                'category' => 'frontend',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Bootstrap',
                'slug' => 'bootstrap',
                'icon' => 'fab fa-bootstrap',
                'color' => '#7952B3',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 6
            ],
            [
                'name' => 'Sass',
                'slug' => 'sass',
                'icon' => 'fab fa-sass',
                'color' => '#CC6699',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 7
            ],
            [
                'name' => 'CSS3',
                'slug' => 'css3',
                'icon' => 'fab fa-css3-alt',
                'color' => '#1572B6',
                'category' => 'frontend',
                'proficiency' => 5,
                'featured' => false,
                'sort_order' => 8
            ],
            [
                'name' => 'HTML5',
                'slug' => 'html5',
                'icon' => 'fab fa-html5',
                'color' => '#E34F26',
                'category' => 'frontend',
                'proficiency' => 5,
                'featured' => false,
                'sort_order' => 9
            ],
            [
                'name' => 'Angular',
                'slug' => 'angular',
                'icon' => 'fab fa-angular',
                'color' => '#DD0031',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => true,
                'sort_order' => 10
            ],
            [
                'name' => 'Next.js',
                'slug' => 'nextjs',
                'icon' => 'fas fa-forward',
                'color' => '#000000',
                'category' => 'frontend',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 11
            ],
            [
                'name' => 'Nuxt.js',
                'slug' => 'nuxtjs',
                'icon' => 'fas fa-mountain',
                'color' => '#00DC82',
                'category' => 'frontend',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 12
            ],
            [
                'name' => 'Svelte',
                'slug' => 'svelte',
                'icon' => 'fas fa-fire',
                'color' => '#FF3E00',
                'category' => 'frontend',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 13
            ],

            // Backend
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'icon' => 'fab fa-laravel',
                'color' => '#FF2D20',
                'category' => 'backend',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 14
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'icon' => 'fab fa-php',
                'color' => '#777BB4',
                'category' => 'backend',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 15
            ],
            [
                'name' => 'Node.js',
                'slug' => 'nodejs',
                'icon' => 'fab fa-node-js',
                'color' => '#339933',
                'category' => 'backend',
                'proficiency' => 4,
                'featured' => true,
                'sort_order' => 16
            ],
            [
                'name' => 'Express.js',
                'slug' => 'expressjs',
                'icon' => 'fas fa-server',
                'color' => '#000000',
                'category' => 'backend',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 17
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'icon' => 'fab fa-python',
                'color' => '#3776AB',
                'category' => 'backend',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 18
            ],
            [
                'name' => 'Django',
                'slug' => 'django',
                'icon' => 'fas fa-python',
                'color' => '#092E20',
                'category' => 'backend',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 19
            ],
            [
                'name' => 'FastAPI',
                'slug' => 'fastapi',
                'icon' => 'fas fa-rocket',
                'color' => '#009688',
                'category' => 'backend',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 20
            ],
            [
                'name' => 'ASP.NET',
                'slug' => 'aspnet',
                'icon' => 'fas fa-code',
                'color' => '#512BD4',
                'category' => 'backend',
                'proficiency' => 2,
                'featured' => false,
                'sort_order' => 21
            ],

            // Database
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'icon' => 'fas fa-database',
                'color' => '#4479A1',
                'category' => 'database',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 22
            ],
            [
                'name' => 'PostgreSQL',
                'slug' => 'postgresql',
                'icon' => 'fas fa-database',
                'color' => '#336791',
                'category' => 'database',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 23
            ],
            [
                'name' => 'MongoDB',
                'slug' => 'mongodb',
                'icon' => 'fas fa-leaf',
                'color' => '#47A248',
                'category' => 'database',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 24
            ],
            [
                'name' => 'SQLite',
                'slug' => 'sqlite',
                'icon' => 'fas fa-database',
                'color' => '#003B57',
                'category' => 'database',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 25
            ],
            [
                'name' => 'Redis',
                'slug' => 'redis',
                'icon' => 'fas fa-server',
                'color' => '#DC382D',
                'category' => 'database',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 26
            ],

            // Tools
            [
                'name' => 'Git',
                'slug' => 'git',
                'icon' => 'fab fa-git-alt',
                'color' => '#F05032',
                'category' => 'tools',
                'proficiency' => 5,
                'featured' => true,
                'sort_order' => 27
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'icon' => 'fab fa-docker',
                'color' => '#2496ED',
                'category' => 'tools',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 28
            ],
            [
                'name' => 'Vite',
                'slug' => 'vite',
                'icon' => 'fas fa-bolt',
                'color' => '#646CFF',
                'category' => 'tools',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 29
            ],
            [
                'name' => 'Webpack',
                'slug' => 'webpack',
                'icon' => 'fas fa-cube',
                'color' => '#8DD6F9',
                'category' => 'tools',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 30
            ],
            [
                'name' => 'VS Code',
                'slug' => 'vscode',
                'icon' => 'fas fa-code',
                'color' => '#007ACC',
                'category' => 'tools',
                'proficiency' => 5,
                'featured' => false,
                'sort_order' => 31
            ],
            [
                'name' => 'Figma',
                'slug' => 'figma',
                'icon' => 'fab fa-figma',
                'color' => '#F24E1E',
                'category' => 'tools',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 32
            ],
            [
                'name' => 'Cloudinary',
                'slug' => 'cloudinary',
                'icon' => 'fas fa-cloud',
                'color' => '#FF6F00',
                'category' => 'tools',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 33
            ],
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'icon' => 'fab fa-stripe',
                'color' => '#635BFF',
                'category' => 'tools',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 34
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
                'icon' => 'fab fa-aws',
                'color' => '#FF9900',
                'category' => 'tools',
                'proficiency' => 3,
                'featured' => false,
                'sort_order' => 35
            ],
            [
                'name' => 'Postman',
                'slug' => 'postman',
                'icon' => 'fas fa-paper-plane',
                'color' => '#FF6C37',
                'category' => 'tools',
                'proficiency' => 4,
                'featured' => false,
                'sort_order' => 36
            ]
        ];

        foreach ($technologies as $technology) {
            Technology::updateOrCreate(
                ['slug' => $technology['slug']],
                $technology
            );
        }
    }
}
