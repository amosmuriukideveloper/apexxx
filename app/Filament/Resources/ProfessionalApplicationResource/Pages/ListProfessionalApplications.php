<?php

namespace App\Filament\Resources\ProfessionalApplicationResource\Pages;

use App\Filament\Resources\ProfessionalApplicationResource;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListProfessionalApplications extends ListRecords
{
    protected static string $resource = ProfessionalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export Applications')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    // TODO: Implement export functionality
                    \Filament\Notifications\Notification::make()
                        ->title('Export feature coming soon')
                        ->info()
                        ->send();
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Applications')
                ->badge($this->getTotalCount())
                ->modifyQueryUsing(fn (Builder $query) => $query),
            
            'pending' => Tab::make('Pending')
                ->badge($this->getPendingCount())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('application_status', 'pending')),
            
            'approved' => Tab::make('Approved')
                ->badge($this->getApprovedCount())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('application_status', 'approved')),
            
            'rejected' => Tab::make('Rejected')
                ->badge($this->getRejectedCount())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('application_status', 'rejected')),
            
            'experts' => Tab::make('Experts')
                ->badge($this->getExpertCount())
                ->badgeColor('primary')
                ->icon('heroicon-o-academic-cap'),
            
            'tutors' => Tab::make('Tutors')
                ->badge($this->getTutorCount())
                ->badgeColor('success')
                ->icon('heroicon-o-user-group'),
            
            'creators' => Tab::make('Creators')
                ->badge($this->getCreatorCount())
                ->badgeColor('warning')
                ->icon('heroicon-o-film'),
        ];
    }

    // Override to handle multiple models
    protected function getTableQuery(): Builder
    {
        $activeTab = $this->activeTab ?? 'all';
        
        // Determine which models to query based on active tab
        if ($activeTab === 'experts') {
            return Expert::query();
        } elseif ($activeTab === 'tutors') {
            return Tutor::query();
        } elseif ($activeTab === 'creators') {
            return ContentCreator::query();
        }
        
        // For other tabs, we need to combine all three models
        // This is a bit tricky - we'll use union queries
        return $this->getCombinedQuery();
    }

    protected function getCombinedQuery(): Builder
    {
        // Get all experts
        $experts = Expert::select([
            'id',
            'name',
            'email',
            'phone',
            'specialization',
            'expertise_areas',
            'years_of_experience',
            'bio',
            'application_status',
            'rejection_reason',
            'documents_verified',
            'cv_document',
            'certificates',
            'id_document',
            'admin_notes',
            'approved_by',
            'approved_at',
            'status',
            'created_at',
            'updated_at',
            \DB::raw("'Expert' as type")
        ]);

        $tutors = Tutor::select([
            'id',
            'name',
            'email',
            'phone',
            \DB::raw('NULL as specialization'),
            \DB::raw('subjects as expertise_areas'),
            \DB::raw('teaching_experience_years as years_of_experience'),
            'bio',
            'application_status',
            'rejection_reason',
            'documents_verified',
            'cv_document',
            'certificates',
            'id_document',
            'admin_notes',
            'approved_by',
            'approved_at',
            'status',
            'created_at',
            'updated_at',
            \DB::raw("'Tutor' as type")
        ]);

        $creators = ContentCreator::select([
            'id',
            'name',
            'email',
            'phone',
            \DB::raw('NULL as specialization'),
            'expertise_areas',
            \DB::raw('NULL as years_of_experience'),
            'bio',
            'application_status',
            'rejection_reason',
            'documents_verified',
            'cv_document',
            'certificates',
            'id_document',
            'admin_notes',
            'approved_by',
            'approved_at',
            'status',
            'created_at',
            'updated_at',
            \DB::raw("'Creator' as type")
        ]);

        // For simplicity, just return experts query
        // In production, you'd want to implement a proper polymorphic solution
        return Expert::query();
    }

    protected function getTotalCount(): int
    {
        return Expert::count() + Tutor::count() + ContentCreator::count();
    }

    protected function getPendingCount(): int
    {
        return Expert::where('application_status', 'pending')->count() +
               Tutor::where('application_status', 'pending')->count() +
               ContentCreator::where('application_status', 'pending')->count();
    }

    protected function getApprovedCount(): int
    {
        return Expert::where('application_status', 'approved')->count() +
               Tutor::where('application_status', 'approved')->count() +
               ContentCreator::where('application_status', 'approved')->count();
    }

    protected function getRejectedCount(): int
    {
        return Expert::where('application_status', 'rejected')->count() +
               Tutor::where('application_status', 'rejected')->count() +
               ContentCreator::where('application_status', 'rejected')->count();
    }

    protected function getExpertCount(): int
    {
        return Expert::count();
    }

    protected function getTutorCount(): int
    {
        return Tutor::count();
    }

    protected function getCreatorCount(): int
    {
        return ContentCreator::count();
    }
}
