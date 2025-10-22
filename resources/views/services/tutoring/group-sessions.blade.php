@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 py-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(59,130,246,0.1),transparent_50%)]"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-6">
                Collaborative Learning Environment
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                Group Tutoring <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">Sessions</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Learn together, save together! Join small group sessions for interactive learning, 
                peer collaboration, and affordable expert guidance.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:scale-105 shadow-lg">
                    Join a Group Session
                    <i data-lucide="users" class="ml-2 h-5 w-5 group-hover:scale-110 transition-transform"></i>
                </a>
                <a href="#schedule" class="inline-flex items-center justify-center border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all">
                    View Schedule
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Benefits of Group Learning -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Group Sessions?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Experience the power of collaborative learning
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $benefits = [
                    ['title' => 'Cost-Effective', 'desc' => 'Save up to 60% compared to one-on-one sessions', 'icon' => 'dollar-sign', 'color' => 'green'],
                    ['title' => 'Peer Learning', 'desc' => 'Learn from questions and perspectives of others', 'icon' => 'users', 'color' => 'blue'],
                    ['title' => 'Interactive Sessions', 'desc' => 'Engaging discussions and group activities', 'icon' => 'message-circle', 'color' => 'purple'],
                    ['title' => 'Motivation & Support', 'desc' => 'Stay motivated with a learning community', 'icon' => 'heart', 'color' => 'red'],
                    ['title' => 'Expert Facilitation', 'desc' => 'Professional tutors guide every session', 'icon' => 'award', 'color' => 'yellow'],
                    ['title' => 'Flexible Schedule', 'desc' => 'Multiple time slots throughout the week', 'icon' => 'clock', 'color' => 'cyan'],
                ];
            @endphp

            @foreach($benefits as $benefit)
            <div class="group bg-gradient-to-br from-{{ $benefit['color'] }}-50 to-white rounded-2xl p-6 border border-{{ $benefit['color'] }}-200 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gradient-to-br from-{{ $benefit['color'] }}-500 to-{{ $benefit['color'] }}-600 rounded-xl flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $benefit['icon'] }}" class="h-7 w-7"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $benefit['title'] }}</h3>
                <p class="text-gray-600">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Available Group Sessions -->
<section id="schedule" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Current Group Sessions</h2>
            <p class="text-xl text-gray-600">Join an ongoing group or start a new one</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @php
                $sessions = [
                    [
                        'subject' => 'Calculus I',
                        'level' => 'Undergraduate',
                        'tutor' => 'Dr. Sarah Johnson',
                        'schedule' => 'Mon, Wed, Fri • 6:00 PM EST',
                        'duration' => '1.5 hours',
                        'enrolled' => 4,
                        'capacity' => 6,
                        'price' => 20,
                        'starts' => 'Jan 15, 2024',
                        'color' => 'blue'
                    ],
                    [
                        'subject' => 'Organic Chemistry',
                        'level' => 'Undergraduate',
                        'tutor' => 'Prof. Michael Chen',
                        'schedule' => 'Tue, Thu • 7:00 PM EST',
                        'duration' => '2 hours',
                        'enrolled' => 5,
                        'capacity' => 6,
                        'price' => 22,
                        'starts' => 'Jan 20, 2024',
                        'color' => 'green'
                    ],
                    [
                        'subject' => 'SAT Math Prep',
                        'level' => 'High School',
                        'tutor' => 'Jennifer Williams',
                        'schedule' => 'Sat, Sun • 10:00 AM EST',
                        'duration' => '2 hours',
                        'enrolled' => 3,
                        'capacity' => 8,
                        'price' => 18,
                        'starts' => 'Jan 13, 2024',
                        'color' => 'purple'
                    ],
                    [
                        'subject' => 'Python Programming',
                        'level' => 'Beginner',
                        'tutor' => 'Alex Thompson',
                        'schedule' => 'Wed, Fri • 8:00 PM EST',
                        'duration' => '1.5 hours',
                        'enrolled' => 6,
                        'capacity' => 8,
                        'price' => 25,
                        'starts' => 'Jan 17, 2024',
                        'color' => 'indigo'
                    ],
                ];
            @endphp

            @foreach($sessions as $session)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="bg-gradient-to-r from-{{ $session['color'] }}-500 to-{{ $session['color'] }}-600 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-1">{{ $session['subject'] }}</h3>
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm">{{ $session['level'] }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-white">${{ $session['price'] }}</div>
                        <div class="text-{{ $session['color'] }}-100 text-sm">per session</div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center text-gray-700">
                            <i data-lucide="user-check" class="h-5 w-5 text-{{ $session['color'] }}-600 mr-3"></i>
                            <span><strong>Tutor:</strong> {{ $session['tutor'] }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i data-lucide="calendar" class="h-5 w-5 text-{{ $session['color'] }}-600 mr-3"></i>
                            <span>{{ $session['schedule'] }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i data-lucide="clock" class="h-5 w-5 text-{{ $session['color'] }}-600 mr-3"></i>
                            <span>{{ $session['duration'] }} per session</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i data-lucide="play-circle" class="h-5 w-5 text-{{ $session['color'] }}-600 mr-3"></i>
                            <span>Starts {{ $session['starts'] }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Group Capacity</span>
                            <span class="text-sm font-bold text-{{ $session['color'] }}-600">{{ $session['enrolled'] }}/{{ $session['capacity'] }} enrolled</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-{{ $session['color'] }}-500 to-{{ $session['color'] }}-600 h-2 rounded-full transition-all" style="width: {{ ($session['enrolled'] / $session['capacity']) * 100 }}%"></div>
                        </div>
                        @if($session['enrolled'] >= $session['capacity'] - 1)
                        <p class="text-red-600 text-sm mt-2 font-semibold">
                            <i data-lucide="alert-circle" class="h-4 w-4 inline mr-1"></i>
                            Almost full! Only {{ $session['capacity'] - $session['enrolled'] }} spot{{ $session['capacity'] - $session['enrolled'] != 1 ? 's' : '' }} left
                        </p>
                        @endif
                    </div>

                    <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-{{ $session['color'] }}-600 to-{{ $session['color'] }}-700 text-white text-center py-3 rounded-xl font-semibold hover:from-{{ $session['color'] }}-700 hover:to-{{ $session['color'] }}-800 transition-all group-hover:scale-105">
                        Join This Group
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Session Schedules -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Weekly Schedule Overview</h2>
            <p class="text-xl text-gray-600">Find the perfect time slot for your learning</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white shadow-xl rounded-2xl overflow-hidden">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold">Time</th>
                        <th class="px-6 py-4 text-left font-semibold">Monday</th>
                        <th class="px-6 py-4 text-left font-semibold">Tuesday</th>
                        <th class="px-6 py-4 text-left font-semibold">Wednesday</th>
                        <th class="px-6 py-4 text-left font-semibold">Thursday</th>
                        <th class="px-6 py-4 text-left font-semibold">Friday</th>
                        <th class="px-6 py-4 text-left font-semibold">Weekend</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $schedule = [
                            ['time' => '10:00 AM', 'mon' => '', 'tue' => '', 'wed' => '', 'thu' => '', 'fri' => '', 'weekend' => 'SAT Prep'],
                            ['time' => '2:00 PM', 'mon' => 'Biology', 'tue' => '', 'wed' => 'Biology', 'thu' => '', 'fri' => 'Biology', 'weekend' => ''],
                            ['time' => '6:00 PM', 'mon' => 'Calculus', 'tue' => 'Chem', 'wed' => 'Calculus', 'thu' => 'Chem', 'fri' => 'Calculus', 'weekend' => ''],
                            ['time' => '8:00 PM', 'mon' => '', 'tue' => 'Stats', 'wed' => 'Python', 'thu' => 'Stats', 'fri' => 'Python', 'weekend' => ''],
                        ];
                    @endphp

                    @foreach($schedule as $index => $slot)
                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $slot['time'] }}</td>
                        <td class="px-6 py-4">
                            @if($slot['mon'])
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['mon'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot['tue'])
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['tue'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot['wed'])
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['wed'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot['thu'])
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['thu'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot['fri'])
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['fri'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot['weekend'])
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">{{ $slot['weekend'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Group Size Limits -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Optimal Group Sizes</h2>
            <p class="text-xl text-gray-600">Small groups ensure quality interaction</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $groupSizes = [
                    ['size' => '3-4 Students', 'title' => 'Mini Group', 'desc' => 'Most interactive, personalized attention', 'icon' => 'users', 'ideal' => 'Complex subjects like Math, Physics'],
                    ['size' => '5-6 Students', 'title' => 'Standard Group', 'desc' => 'Perfect balance of interaction and diversity', 'icon' => 'users', 'ideal' => 'Most subjects, test prep'],
                    ['size' => '7-8 Students', 'title' => 'Large Group', 'desc' => 'Great for discussions and peer learning', 'icon' => 'users', 'ideal' => 'Languages, Humanities, SAT prep'],
                ];
            @endphp

            @foreach($groupSizes as $group)
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="{{ $group['icon'] }}" class="h-10 w-10 text-white"></i>
                </div>
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $group['size'] }}</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $group['title'] }}</h3>
                <p class="text-gray-600 mb-4">{{ $group['desc'] }}</p>
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Ideal for:</p>
                    <p class="text-sm text-gray-600">{{ $group['ideal'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Can't find the right group?</h3>
            <p class="text-gray-700 mb-6">Create your own group and we'll help you find like-minded learners!</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all">
                Request Custom Group
                <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
            </a>
        </div>
    </div>
</section>

<!-- Discount Pricing -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Group Session Pricing</h2>
            <p class="text-xl text-blue-100">Save significantly with group learning</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $pricing = [
                    ['sessions' => 'Single Session', 'price' => '25', 'savings' => 'Pay as you go', 'total' => '25'],
                    ['sessions' => '4 Sessions', 'price' => '22', 'savings' => 'Save 12%', 'total' => '88', 'popular' => false],
                    ['sessions' => '8 Sessions', 'price' => '20', 'savings' => 'Save 20%', 'total' => '160', 'popular' => true],
                    ['sessions' => '12 Sessions', 'price' => '18', 'savings' => 'Save 28%', 'total' => '216', 'popular' => false],
                ];
            @endphp

            @foreach($pricing as $plan)
            <div class="relative bg-white rounded-2xl p-6 {{ isset($plan['popular']) && $plan['popular'] ? 'ring-4 ring-yellow-400 transform scale-105' : '' }}">
                @if(isset($plan['popular']) && $plan['popular'])
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-yellow-400 text-gray-900 px-4 py-1 rounded-full text-sm font-bold">Best Value</span>
                </div>
                @endif

                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $plan['sessions'] }}</h3>
                    <div class="text-5xl font-bold text-gray-900 mb-2">${{ $plan['price'] }}</div>
                    <p class="text-gray-600 mb-1">per session</p>
                    <p class="text-green-600 font-semibold text-sm mb-4">{{ $plan['savings'] }}</p>
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <p class="text-sm text-gray-600">Total: <span class="font-bold text-gray-900">${{ $plan['total'] }}</span></p>
                    </div>
                    <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all">
                        Select Plan
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <p class="text-center text-blue-100 mt-8">
            <i data-lucide="info" class="inline h-5 w-5 mr-2"></i>
            All plans include access to session recordings and study materials
        </p>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-gray-900 mb-6">Join a Group Session Today</h2>
        <p class="text-xl text-gray-600 mb-8">
            Learn collaboratively, achieve collectively
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-10 py-5 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-cyan-700 transition-all transform hover:scale-105 shadow-2xl">
            Browse Available Groups
            <i data-lucide="arrow-right" class="ml-3 h-6 w-6"></i>
        </a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endsection
