export function Logo({ className = "" }: { className?: string }) {
  return (
    <div className={`flex items-center gap-2 ${className}`}>
      <div className="h-6 w-6 rounded-md bg-primary" />
      <span className="font-semibold">Uplifta</span>
    </div>
  );
}


