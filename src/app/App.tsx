import { 
  Video, 
  FileText, 
  ClipboardCheck, 
  PlayCircle, 
  MessageCircle
} from 'lucide-react';
import { LinkCard } from './components/LinkCard';

export default function App() {
  const actionCards = [
    {
      icon: Video,
      title: 'Join Zoom Session',
      description: 'Access live training and interactive workshops',
      href: '#zoom'
    },
    {
      icon: FileText,
      title: 'Training Materials',
      description: 'Download presentations and course documents',
      href: '#materials'
    },
    {
      icon: ClipboardCheck,
      title: 'Mark Attendance',
      description: 'Register your participation for today',
      href: '#attendance'
    },
    {
      icon: PlayCircle,
      title: 'Session Recordings',
      description: 'Watch previous sessions and replays',
      href: '#recordings'
    },
    {
      icon: MessageCircle,
      title: 'WhatsApp Group',
      description: 'Join our community for updates and discussion',
      href: '#whatsapp'
    }
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
      {/* Hero Section */}
      <section className="px-4 pt-16 pb-12">
        <div className="max-w-5xl mx-auto text-center">
          <div className="inline-block px-4 py-2 bg-primary/10 rounded-full mb-6">
            <span className="text-sm font-medium text-primary">Government Training & Development</span>
          </div>
          
          <h1 className="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
            Professional Development
            <br />
            <span className="bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">
              Summit 2026
            </span>
          </h1>
          
          <p className="text-xl text-gray-600 max-w-2xl mx-auto mb-12 leading-relaxed">
            Welcome to your comprehensive training hub. Access all resources, join live sessions, 
            and connect with fellow participants in one place.
          </p>
        </div>

        {/* Action Cards Grid */}
        <div className="max-w-5xl mx-auto">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {actionCards.map((card, index) => (
              <LinkCard
                key={index}
                icon={card.icon}
                title={card.title}
                description={card.description}
                href={card.href}
              />
            ))}
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="px-4 py-8 mt-12">
        <div className="max-w-5xl mx-auto text-center">
          <p className="text-sm text-gray-500 mb-2">
            Need help? Contact us at support@training.gov
          </p>
          <p className="text-xs text-gray-400">
            © 2026 Government Training & Development. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}