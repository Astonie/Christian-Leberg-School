"use client";

import { useTheme } from "next-themes";
import { Moon, Sun, Bell, Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import { MobileSidebar } from "./MobileSidebar";

export function Topbar() {
  const { theme, setTheme } = useTheme();
  const [query, setQuery] = useState("");
  return (
    <header className="h-14 border-b flex items-center gap-2 px-4">
      <MobileSidebar />
      <div className="flex-1 flex items-center gap-2">
        <div className="relative w-full max-w-xs">
          <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
          <input
            aria-label="Search"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Search..."
            className="w-full rounded-md border bg-background pl-8 pr-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
          />
        </div>
      </div>
      <Button variant="ghost" size="icon" aria-label="notifications" className="relative">
        <Bell className="h-5 w-5" />
        <span className="absolute right-1 top-1 inline-flex h-2 w-2 rounded-full bg-destructive" />
      </Button>
      <Button
        variant="ghost"
        size="icon"
        aria-label="Toggle theme"
        onClick={() => setTheme(theme === "dark" ? "light" : "dark")}
      >
        <Sun className="h-5 w-5 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
        <Moon className="absolute h-5 w-5 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
      </Button>
      <div className="ml-2 text-sm">Admin</div>
    </header>
  );
}


