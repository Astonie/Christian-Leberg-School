"use client";

import { AreaChart, Area, ResponsiveContainer } from "recharts";

export function AreaSparkline({ data, color = "#3b82f6" }: { data: { value: number }[]; color?: string }) {
  return (
    <div className="h-10 w-full">
      <ResponsiveContainer>
        <AreaChart data={data} margin={{ top: 0, bottom: 0, left: 0, right: 0 }}>
          <defs>
            <linearGradient id="spark" x1="0" y1="0" x2="0" y2="1">
              <stop offset="5%" stopColor={color} stopOpacity={0.4} />
              <stop offset="95%" stopColor={color} stopOpacity={0} />
            </linearGradient>
          </defs>
          <Area type="monotone" dataKey="value" stroke={color} fill="url(#spark)" strokeWidth={2} />
        </AreaChart>
      </ResponsiveContainer>
    </div>
  );
}


