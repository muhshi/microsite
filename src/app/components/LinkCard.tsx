import { LucideIcon } from 'lucide-react';

interface LinkCardProps {
  icon: LucideIcon;
  title: string;
  description: string;
  href: string;
}

export function LinkCard({ icon: Icon, title, description, href }: LinkCardProps) {
  return (
    <a
      href={href}
      className="block bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group hover:-translate-y-1"
    >
      <div className="flex flex-col items-center text-center gap-4">
        <div className="w-16 h-16 rounded-xl bg-gradient-to-br from-primary/10 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <Icon className="w-8 h-8 text-primary" />
        </div>
        <div>
          <h3 className="font-semibold text-gray-900 mb-2 text-lg">
            {title}
          </h3>
          <p className="text-sm text-gray-600 leading-relaxed">
            {description}
          </p>
        </div>
      </div>
    </a>
  );
}