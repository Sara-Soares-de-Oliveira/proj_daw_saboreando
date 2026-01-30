<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Recipe;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usersData = [
            ['name' => 'Ana Costa', 'email' => 'ana.costa@example.com'],
            ['name' => 'Bruno Rocha', 'email' => 'bruno.rocha@example.com'],
            ['name' => 'Carla Mendes', 'email' => 'carla.mendes@example.com'],
            ['name' => 'Diogo Silva', 'email' => 'diogo.silva@example.com'],
            ['name' => 'Eva Martins', 'email' => 'eva.martins@example.com'],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $users[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'explorador',
                ]
            );
        }

        $recipesData = [
            [
                'titulo' => 'Bacalhau com Natas',
                'descricao' => 'Clássico português cremoso, perfeito para um almoço de domingo.',
                'foto' => 'recipes/Bacalhau com natas.png',
                'ingredientes' => [
                    '500 g de bacalhau demolhado e desfiado',
                    '800 g de batata em cubos',
                    '1 cebola grande fatiada',
                    '2 dentes de alho picados',
                    '200 ml de natas',
                    '200 ml de leite',
                    '2 c. sopa de azeite',
                    'Sal, pimenta e noz-moscada',
                    'Queijo ralado para gratinar',
                ],
                'modo_preparo' => [
                    'Coza o bacalhau 5 minutos e desfie.',
                    'Frite as batatas em cubos até dourar e reserve.',
                    'Refogue a cebola e o alho no azeite.',
                    'Junte o bacalhau, tempere e misture as batatas.',
                    'Adicione leite e natas, envolva e vá ao forno a gratinar.',
                ],
                'dificuldade' => 'medio',
            ],
            [
                'titulo' => 'Bacalhau à Brás',
                'descricao' => 'Bacalhau desfiado com batata palha, ovos e salsa.',
                'foto' => 'recipes/Bacalhau a bras.png',
                'ingredientes' => [
                    '400 g de bacalhau demolhado e desfiado',
                    '300 g de batata palha',
                    '1 cebola em meias-luas',
                    '2 dentes de alho picados',
                    '5 ovos',
                    'Azeitonas pretas',
                    'Salsa picada',
                    'Azeite, sal e pimenta',
                ],
                'modo_preparo' => [
                    'Refogue a cebola e o alho em azeite.',
                    'Junte o bacalhau e cozinhe alguns minutos.',
                    'Adicione a batata palha e envolva.',
                    'Bata os ovos, junte ao tacho e mexa até cremoso.',
                    'Finalize com salsa e azeitonas.',
                ],
                'dificuldade' => 'facil',
            ],
            [
                'titulo' => 'Picanha na Frigideira com Alho',
                'descricao' => 'Picanha suculenta e rápida, com sabor a alho e manteiga.',
                'foto' => 'recipes/Picanha.png',
                'ingredientes' => [
                    '600 g de picanha em bifes',
                    '2 dentes de alho esmagados',
                    '2 c. sopa de manteiga',
                    'Sal grosso e pimenta',
                    'Ramos de alecrim (opcional)',
                ],
                'modo_preparo' => [
                    'Tempere a picanha com sal e pimenta.',
                    'Aqueça a frigideira e sele os bifes de ambos os lados.',
                    'Junte manteiga, alho e alecrim.',
                    'Regue a carne com a manteiga derretida por 1 a 2 minutos.',
                    'Deixe repousar e sirva.',
                ],
                'dificuldade' => 'medio',
            ],
            [
                'titulo' => 'Batata Gratinada Cremosa',
                'descricao' => 'Camadas de batata com natas e queijo, gratinadas no forno.',
                'foto' => 'recipes/Batata gratinada1.png',
                'ingredientes' => [
                    '800 g de batata em rodelas finas',
                    '250 ml de natas',
                    '200 ml de leite',
                    '1 dente de alho',
                    'Noz-moscada, sal e pimenta',
                    '150 g de queijo ralado',
                ],
                'modo_preparo' => [
                    'Esfregue um dente de alho no tabuleiro.',
                    'Disponha as batatas em camadas e tempere.',
                    'Misture natas e leite e verta por cima.',
                    'Cubra com queijo ralado.',
                    'Leve ao forno até dourar.',
                ],
                'dificuldade' => 'facil',
            ],
            [
                'titulo' => 'Batata Gratinada com Ervas',
                'descricao' => 'Versão aromática com ervas frescas e queijo.',
                'foto' => 'recipes/batata gratinada.png',
                'ingredientes' => [
                    '700 g de batata em rodelas finas',
                    '200 ml de natas',
                    '200 ml de leite',
                    '1 c. sopa de azeite',
                    'Tomilho e salsa picados',
                    'Sal e pimenta',
                    '120 g de queijo ralado',
                ],
                'modo_preparo' => [
                    'Pré-aqueça o forno a 200ºC.',
                    'Misture natas, leite, azeite e ervas.',
                    'Alterne batata e o creme aromático numa assadeira.',
                    'Tempere e finalize com queijo ralado.',
                    'Asse até ficar dourada e macia.',
                ],
                'dificuldade' => 'facil',
            ],
        ];

        $recipes = [];
        foreach ($recipesData as $index => $data) {
            $owner = $users[$index % count($users)];
            $recipes[] = Recipe::updateOrCreate(
                ['titulo' => $data['titulo']],
                [
                    'user_id' => $owner->id,
                    'descricao' => $data['descricao'],
                    'ingredientes' => implode("\n", $data['ingredientes']),
                    'modo_preparo' => implode("\n", $data['modo_preparo']),
                    'dificuldade' => $data['dificuldade'],
                    'estado' => 'aprovado',
                    'foto' => $data['foto'] ?? null,
                ]
            );
        }

        $pendingRecipesData = [
            [
                'titulo' => 'Bacalhau com Natas Tradicional',
                'descricao' => 'Versão tradicional para validação da moderação.',
                'foto' => 'recipes/Bacalhau com natas.png',
                'ingredientes' => [
                    '500 g de bacalhau demolhado',
                    '600 g de batata em cubos',
                    '1 cebola picada',
                    '200 ml de natas',
                    'Azeite, sal e pimenta',
                ],
                'modo_preparo' => [
                    'Coza o bacalhau e desfie.',
                    'Refogue a cebola e junte o bacalhau.',
                    'Misture as batatas e envolva com as natas.',
                    'Leve ao forno até dourar.',
                ],
                'dificuldade' => 'medio',
            ],
            [
                'titulo' => 'Picanha ao Alho e Alecrim',
                'descricao' => 'Receita submetida para aprovação.',
                'foto' => 'recipes/Picanha.png',
                'ingredientes' => [
                    '500 g de picanha',
                    '2 dentes de alho',
                    'Alecrim fresco',
                    'Sal grosso e pimenta',
                    'Azeite',
                ],
                'modo_preparo' => [
                    'Tempere a picanha com sal e pimenta.',
                    'Sele em lume alto com azeite e alho.',
                    'Finalize com alecrim e deixe repousar.',
                ],
                'dificuldade' => 'medio',
            ],
        ];

        foreach ($pendingRecipesData as $index => $data) {
            $owner = $users[($index + 2) % count($users)];
            Recipe::updateOrCreate(
                ['titulo' => $data['titulo']],
                [
                    'user_id' => $owner->id,
                    'descricao' => $data['descricao'],
                    'ingredientes' => implode("\n", $data['ingredientes']),
                    'modo_preparo' => implode("\n", $data['modo_preparo']),
                    'dificuldade' => $data['dificuldade'],
                    'estado' => 'pendente',
                    'foto' => $data['foto'] ?? null,
                ]
            );
        }

        $commentTexts = [
            'Ficou delicioso, já vou repetir!',
            'Muito boa receita, fácil de seguir.',
            'Gostei do tempero, ficou no ponto.',
            'Excelente, toda a família aprovou.',
            'Vou guardar esta receita, obrigada!',
        ];

        foreach ($recipes as $recipe) {
            for ($i = 0; $i < 5; $i++) {
                $author = $users[($i + 1) % count($users)];
                Comment::updateOrCreate(
                    [
                        'recipe_id' => $recipe->id,
                        'user_id' => $author->id,
                        'conteudo' => $commentTexts[$i % count($commentTexts)],
                    ],
                    ['estado' => 'ativo']
                );
            }
        }

        $reportReasons = [
            'Conteúdo inadequado',
            'Comentário ofensivo',
            'Spam',
            'Informação incorreta',
            'Linguagem imprópria',
        ];

        $comments = Comment::query()->take(5)->get();
        foreach ($comments as $index => $comment) {
            $reporter = $users[($index + 2) % count($users)];
            Report::updateOrCreate(
                [
                    'comment_id' => $comment->id,
                    'user_id' => $reporter->id,
                ],
                ['motivo' => $reportReasons[$index % count($reportReasons)]]
            );
        }
    }
}
