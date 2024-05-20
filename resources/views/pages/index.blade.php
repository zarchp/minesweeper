<?php

use function Livewire\Volt\{state};

state([
    'myModal2' => false,
]);

$numbers = [4, 6, 1, 3, 1, 5];
$count = count($numbers);
for ($i = $count - 2; $i >= 0; $i--) {
    $numbers[$i] += $numbers[$i + 1];
}
print_r($count);

?>

<x-layouts.app>
    @volt
        <div x-data="{
            field: [],
            width: 10,
            height: 10,
            mines: 10,
            isLose: false,
            isWin: false,

            init() {
                this.isLose = false;
                this.isWin = false;
                this.hideModal();
                this.generateField();
            },

            generateField() {
                this.field = [];
                for (let y = 0; y < this.height; y++) {
                    this.field[y] = [];
                    for (let x = 0; x < this.width; x++) {
                        let rand = Math.random() < 0.1 ? 'B' : 0;
                        console.log(rand);
                        this.field[y][x] = {
                            x,
                            y,
                            value: 0,
                            uncovered: false,
                            flagged: false,
                        };
                    }
                }

                for (let i = 0; i < this.mines; i++) {
                    let x, y;
                    do {
                        x = Math.floor(Math.random() * this.width);
                        y = Math.floor(Math.random() * this.height);
                    } while (this.field[y][x].value === 'B');
                    this.field[y][x].value = 'B';
                    this.field[y][x].uncovered = false;
                }

                for (let y = 0; y < this.height; y++) {
                    for (let x = 0; x < this.width; x++) {
                        if (this.field[y][x].value !== 'B') {
                            let count = 0;
                            for (let dy = -1; dy <= 1; dy++) {
                                for (let dx = -1; dx <= 1; dx++) {
                                    let ny = y + dy;
                                    let nx = x + dx;
                                    if (ny >= 0 && ny < this.height && nx >= 0 && nx < this.width) {
                                        if (this.field[ny][nx].value === 'B') {
                                            count++;
                                        }
                                    }
                                }
                            }
                            this.field[y][x].value = count;
                        }
                    }
                }
            },

            getCellText(cell) {
                if (cell.uncovered) {
                    return cell.value === 'B' ? '💣' : cell.value === 0 ? '' : cell.value;
                } else if (cell.flagged) {
                    return '🚩';
                } else {
                    return '';
                }
            },

            uncoverCell(cell) {
                if (cell.flagged || this.isWin || this.isLose) {
                    return;
                }

                if (cell.value === 'B') {
                    {{-- alert('Game over!'); --}}
                    this.setLose();
                } else {
                    cell.uncovered = true;
                    if (cell.value === 0) {
                        this.uncoverNeighbours(cell.x, cell.y);
                    }
                }

                let uncoveredCells = this.field.flat().filter((cell) => cell.uncovered && cell.value !== 'B').length;
                let allCells = this.width * this.height - this.mines;

                if (uncoveredCells === allCells) {
                    this.setWin();
                }
            },

            uncoverNeighbours(x, y) {
                const neighbours = this.getNeighbouringCoords(x, y);
                neighbours.forEach(([x, y]) => {
                    if (!this.field[y][x].uncovered) {
                        this.uncoverCell(this.field[y][x]);
                    }
                });
            },

            getNeighbouringCoords(x, y) {
                const neighbours = [];
                for (let dy = -1; dy <= 1; dy++) {
                    for (let dx = -1; dx <= 1; dx++) {
                        if (dx === 0 && dy === 0) continue;
                        const nx = x + dx;
                        const ny = y + dy;
                        if (nx >= 0 && nx < this.width && ny >= 0 && ny < this.height) {
                            neighbours.push([nx, ny]);
                        }
                    }
                }
                return neighbours;
            },

            flagCell(cell) {
                cell.flagged = !cell.flagged;
                let flaggedMines = this.field.flat().filter((cell) => cell.value === 'B' && cell.flagged).length;
                {{-- if (flaggedMines === this.mines) {
                    this.setWin();
                } --}}
            },

            toggleMines() {
                let mines = this.field.flat().find((cell, index) => {
                    if (cell.value !== 'B') {
                        return;
                    }
                    this.field[cell.y][cell.x] = {
                        x: cell.x,
                        y: cell.y,
                        value: 'B',
                        uncovered: !cell.uncovered,
                        flagged: false,
                    };
                });
            },

            laughConfetti() {
                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                var count = 200;
                var scalar = 2;
                var emoji = confetti.shapeFromText({ text: '😂', scalar });
                var defaults = {
                    origin: { y: 0.85 },
                    shapes: [emoji],
                    zIndex: 999,
                };

                function fire(particleRatio, opts) {
                    confetti({
                        ...defaults,
                        ...opts,
                        particleCount: Math.floor(count * particleRatio)
                    });
                }

                fire(0.25, {
                    spread: 26,
                    startVelocity: 55,
                    decay: 0.9,
                    scalar: 5
                });
                fire(0.2, {
                    spread: 60,
                    scalar: 3
                });
                fire(0.35, {
                    spread: 100,
                    decay: 0.91,
                    scalar: 2
                });
                fire(0.1, {
                    spread: 120,
                    startVelocity: 25,
                    decay: 0.92,
                    scalar: 1
                });
                fire(0.1, {
                    spread: 120,
                    startVelocity: 45,
                    scalar: 4
                });
            },

            normalConfetti() {
                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                var count = 200;
                var scalar = 2;
                var defaults = {
                    origin: { y: 0.85 },
                    zIndex: 999,
                };

                function fire(particleRatio, opts) {
                    confetti({
                        ...defaults,
                        ...opts,
                        particleCount: Math.floor(count * particleRatio)
                    });
                }

                fire(0.25, {
                    spread: 26,
                    startVelocity: 55,
                });
                fire(0.2, {
                    spread: 60,
                });
                fire(0.35, {
                    spread: 100,
                    decay: 0.91,
                    scalar: 0.8
                });
                fire(0.1, {
                    spread: 120,
                    startVelocity: 25,
                    decay: 0.92,
                    scalar: 1.2
                });
                fire(0.1, {
                    spread: 120,
                    startVelocity: 45,
                });
            },

            showModal() {
                $wire.myModal2 = true;
            },

            hideModal() {
                $wire.myModal2 = false;
            },

            setLose() {
                this.isLose = true;
                this.showModal();
                this.laughConfetti();
                this.toggleMines();
            },

            setWin() {
                this.isWin = true;
                this.showModal();
                this.normalConfetti();
            }

        }" x-init="">
            <x-header title="Minesweeper" size="text-3xl text-primary">
                <x-slot:actions>
                    <x-theme-toggle class="btn" title="Toggle Theme" darkTheme="dark" lightTheme="fantasy" />
                    <x-button label="" class="" x-on:click="$wire.myModal2 = true" responsive
                        icon="o-adjustments-horizontal" title="Settings" />
                </x-slot:actions>
            </x-header>

            <div class="container flex flex-col items-center justify-center gap-0 mx-auto">
                {{-- <div class="flex justify-between w-full max-w-sm lg:max-w-[440px] mb-2">
                    <div>
                        <x-button label="" icon="o-light-bulb" class="btn-warning btn-circle btn-sm"
                            tooltip-right="Hint" x-on:click="hint()" />
                    </div>
                    <div class="text-xl" x-text="formatTime()">00:00</div>
                    <div class="flex gap-2">
                        <x-button label="" icon="s-check" class="btn-primary btn-circle btn-sm"
                            tooltip-right="Validate" x-on:click="validate()" />
                    </div>
                </div> --}}

                {{-- <div class="relative grid gap-0 border-4 border-stone-600 grid-rows-9"> --}}

                {{-- <div>
                    <label for="width">Width:</label>
                    <input type="number" id="width" v-model="width" min="5" max="50">
                    <label for="height">Height:</label>
                    <input type="number" id="height" v-model="height" min="5" max="50">
                    <label for="mines">Mines:</label>
                    <input type="number" id="mines" v-model="mines" min="1" max="250">
                </div> --}}

                {{-- <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Job</th>
                                <th>Favorite Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                            <tr>
                                <th>1</th>
                                <td>Cy Ganderton</td>
                                <td>Quality Control Specialist</td>
                                <td>Blue</td>
                            </tr>
                            <!-- row 2 -->
                            <tr>
                                <th>2</th>
                                <td>Hart Hagerty</td>
                                <td>Desktop Support Technician</td>
                                <td>Purple</td>
                            </tr>
                            <!-- row 3 -->
                            <tr>
                                <th>3</th>
                                <td>Brice Swyre</td>
                                <td>Tax Accountant</td>
                                <td>Red</td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}

                <div class="grid gap-0 grid-rows-10 border-[1px]">
                    <template x-for="(row, index) in field" x-bind:key="index">
                        <div class="flex">
                            <template x-for="(cell, index2) in row" x-bind:key="index2">
                                <div class="flex items-center justify-center w-10 h-10 border-[1px] border-gray-300 cursor-pointer"
                                    :class="{
                                        'bg-red-200': cell.uncovered && cell.value === 'B',
                                        'bg-gray-100': cell.uncovered,
                                        'bg-yellow-400': cell.flagged,
                                    }"
                                    @click="uncoverCell(cell)" @contextmenu.prevent="flagCell(cell)">
                                    <span x-text="getCellText(cell)"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="flex justify-center gap-4 mt-4">
                    <x-button label="New Game" class="w-full btn-primary" x-on:click="init();" />
                    <x-button label="Cheat" class="btn-outline btn-error" x-on:click="toggleMines()" />
                </div>
                {{-- </div> --}}
            </div>

            <x-modal wire:model="myModal2" class="">
                <div>
                    <div class="flex justify-end w-full">
                        <div class="btn-circle btn-ghost btn-outline btn btn-xs" x-on:click="$wire.myModal2 = false">X</div>
                    </div>
                    <div class="flex flex-col gap-2 mb-8" x-show="isLose">
                        <div class="mb-2 text-6xl font-bold text-center text-error">
                            YOU LOSE!
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mb-8" x-show="isWin">
                        <div class="mb-2 text-6xl font-bold text-center text-success">
                            YOU WIN!
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <x-button label="New Game" class="w-full btn-primary" x-on:click="init();" />
                    </div>
                </div>
            </x-modal>
        </div>
    @endvolt
</x-layouts.app>
