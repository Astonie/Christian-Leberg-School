"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ChevronDown } from "lucide-react";
import { useState } from "react";
import { cn } from "@/lib/utils";
import { navItems } from "./nav";

export function NavList({ className = "" }: { className?: string }) {
  const pathname = usePathname();
  const [open, setOpen] = useState<Record<string, boolean>>({});
  return (
    <nav className={cn("flex-1 overflow-y-auto p-2 space-y-1", className)}>
      {navItems.map((item) => {
        const isActive = item.href && pathname.startsWith(item.href);
        const hasChildren = !!item.children?.length;
        const isOpen = open[item.label] || isActive;
        return (
          <div key={item.label}>
            {hasChildren ? (
              <button
                className={cn(
                  "w-full flex items-center gap-2 rounded-md px-3 py-2 text-left hover:bg-accent",
                  isActive && "bg-accent"
                )}
                onClick={() => setOpen((s) => ({ ...s, [item.label]: !s[item.label] }))}
              >
                {item.icon}
                <span className="flex-1">{item.label}</span>
                <ChevronDown className={cn("h-4 w-4 transition-transform", isOpen && "rotate-180")} />
              </button>
            ) : (
              <Link
                href={item.href || "#"}
                className={cn(
                  "flex items-center gap-2 rounded-md px-3 py-2 hover:bg-accent",
                  isActive && "bg-accent"
                )}
              >
                {item.icon}
                {item.label}
              </Link>
            )}
            {hasChildren && isOpen && (
              <div className="mt-1 space-y-1 pl-8">
                {item.children!.map((child) => (
                  <Link
                    key={child.href}
                    href={child.href}
                    className={cn(
                      "flex items-center gap-2 rounded-md px-3 py-1.5 text-sm hover:bg-accent",
                      pathname.startsWith(child.href) && "bg-accent"
                    )}
                  >
                    {child.label}
                  </Link>
                ))}
              </div>
            )}
          </div>
        );
      })}
    </nav>
  );
}


