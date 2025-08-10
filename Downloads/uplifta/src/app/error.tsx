"use client";

export default function Error({ error, reset }: { error: Error; reset: () => void }) {
  return (
    <div className="min-h-[60vh] grid place-items-center p-6">
      <div className="max-w-md text-center space-y-4">
        <h1 className="text-2xl font-semibold">Something went wrong</h1>
        <p className="text-muted-foreground text-sm">{error.message}</p>
        <button className="rounded-md border px-4 py-2" onClick={reset}>Try again</button>
      </div>
    </div>
  );
}


