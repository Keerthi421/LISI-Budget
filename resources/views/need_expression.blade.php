@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-emetteur.css') }}">
    <link rel="stylesheet" href="{{ asset('css/framework.css') }}">
@endsection

@section('title', 'Expression de Besoin - Engagement')

@section('content')
<div class="page d-flex">
    @include('partials.sidebar_emetteur')
    <div class="content flex-1">
        @include('partials.header')
        
        <div class="section-content p-15">
            <h1 class="content-title mb-20">
                Engagement
            </h1>
            <!-- Budget Line Section -->
            <section class="budget-line bg-white rad-6 p-15 mb-10">
                <h2 class="m-15-0">Ligne Budgétaire </h2>
                
                <div class="form-group mb-20">
                    <label for="budget-select" class="d-block pl-5 mb-10 fw-bold c-777">
                        Sélectionner une ligne budgétaire <span class="red-c fs-18 fw-bold">*</span>
                    </label>
                    <select id="budget-select" class="p-10 rad-6 border full-w mb-10">
                        <option value="">-- Choisir une ligne --</option>
                        @foreach($approvedBudgetLines as $line)
                            <option 
                                value="{{ $line->id }}"
                                data-code="{{ $line->budgetLine->code ?? '' }}"
                                data-dotation="{{ number_format($line->proposed_amount, 2) }}"
                                data-engaged="{{ number_format($line->spend, 2) }}"
                                data-balance="{{ number_format($line->proposed_amount - $line->spend, 2) }}"
                            >
                                {{ $line->budgetLine->name}}
                            </option>
                        @endforeach
                    </select>
                    <div class="budget-details">
                        <div class="table-responsive">
                            <table class="table full-w">
                                <thead>
                                    <tr class="bg-eee">
                                        <th class="p-15 txt-l fw-bold">Code</th>
                                        <th class="p-15 txt-l fw-bold">Dotation</th>
                                        <th class="p-15 txt-l fw-bold">Engagé</th>
                                        <th class="p-15 txt-l fw-bold">Reliquat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-15 fw-bold" id="budget-code">-</td>
                                        <td class="p-15" id="budget-dotation">0.00 DH</td>
                                        <td class="p-15" id="budget-engaged">0.00 DH</td>
                                        <td class="p-15" id="budget-balance">0.00 DH</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Need Section -->
            <section class="items-section bg-white rad-6 p-15 mb-10">
                <h2 class="m-15-0">Besoins </h2> 
                <p class="mb-10 pl-5 c-777">
                    Veuillez écrire ici votre besoin <span class="red-c fs-18 fw-bold">*</span>
                </p>
                <!-- Add Item Form -->
                <div class="add-item-form p-20 bg-f9 rad-6 mb-20">
                    <div class="form-row d-flex mb-15 gap-20">
                        <div class="form-group flex-1">
                            <label for="item-name" class="d-block mb-5 fw-bold">
                                Nature du Besoin
                            </label>
                            <input type="text" id="item-name" placeholder="Nature" class="p-10 rad-6 border-ccc full-w" disabled>
                        </div>
                        <div class="form-group flex-1">
                            <label for="item-qty" class="d-block mb-5 fw-bold">
                                Quantité
                            </label>
                            <input type="number" id="item-qty" min="1" value="1" 
                                class="p-10 rad-6 border-ccc full-w" disabled>
                        </div>
                    </div>

                    <div class="form-row d-flex mb-15 gap-20">
                        <div class="form-group flex-1">
                            <label for="item-description" class="d-block mb-5 fw-bold">
                                Description
                            </label>
                            <input type="text" id="item-description" placeholder="Description" class="p-10 rad-6 border-ccc full-w" disabled>
                        </div>
                    </div>
                    
                    <div class="form-row d-flex mb-15 gap-20">
                        <div class="form-group flex-1">
                            <label for="item-price" class="d-block mb-5 fw-bold">
                                Prix Unitaire Estimé (DH)
                            </label>
                            <input type="number" id="item-price" min="0" step="0.01" 
                                placeholder="0.00" class="p-10 rad-6 border-ccc full-w" disabled>
                        </div>
                        <div class="form-group flex-1">
                            <label for="item-total" class="d-block mb-5 fw-bold">
                                Total (DH)
                            </label>
                            <input type="text" id="item-total" value="0.00" readonly class="p-10 rad-6 border-ccc full-w bg-eee" disabled>
                            <div id="add-item-error" class="red-c fs-14 mt-5 fw-bold" style="display: none;"></div>
                        </div>
                    </div>
                    
                    <div class="form-actions d-flex flex-end gap-5">
                        <button id="add-item-btn" type="button" class="btn btn-primary rad-6 bg-blue c-white p-10 pointer d-flex gap-5" disabled>
                            <i class="fas fa-plus-circle mr-5"></i> Ajouter Article
                        </button>
                    </div>
                </div>
                
                <!-- Items List Table -->
                <div class="items-list table-responsive">
                    <table class="table full-w">
                        <thead>
                            <tr class="bg-eee">
                                <th class="p-15 txt-l fw-bold">Nature</th>
                                <th class="p-15 txt-l fw-bold">Description</th>
                                <th class="p-15 txt-l fw-bold">Prix Unitaire Estimé (DH)</th>
                                <th class="p-15 txt-l fw-bold">Quantité</th>
                                <th class="p-15 txt-l fw-bold">Total (DH)</th>
                                <th class="p-15 txt-l fw-bold">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body">
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Totals Section -->
                <div class="total-row grand-total fit-content gap-20 ml-auto mt-20 d-flex flex-between p-10 bg-blue c-white rad-6">
                    <span class="fw-bold fs-18">TOTAL :</span>
                    <span id="final-total" class="fw-bold fs-18">0.00 DH</span>
                </div>
                <!-- Footer Actions -->
                <footer class="footer-actions d-flex flex-end pt-20 gap-15 border-top">
                    <button id="submit-need-proposition" class="btn btn-primary rad-6 bg-green c-white p-12 pointer d-flex gap-10 p-10">
                        Valider le Proposition <i class="fas fa-check"></i>
                    </button>
                </footer>
            </section>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/need_expression.js') }}"></script>
@endsection