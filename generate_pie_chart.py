import sys
import matplotlib.pyplot as plt

def create_pie_chart(income, expenses, balance):
    sizes = [income, expenses, balance]
    labels = ['Income', 'Expenses', 'Balance']
    colors = ['#6BAD55', '#FD7A6E', '#FFB125']
    explode = (0.1, 0, 0)

    if any(size < 0 for size in sizes):
        raise ValueError("All sizes must be non-negative values.")

    fig, ax = plt.subplots(figsize=(8, 8))
    fig.patch.set_facecolor('#FCF5E9')
    
    wedges, texts, autotexts = ax.pie(
        sizes, explode=explode, labels=labels, colors=colors, autopct='%1.1f%%',
        shadow=True, startangle=90, wedgeprops=dict(edgecolor='black')
    )

    for autotext in autotexts:
        autotext.set_fontsize(14)
    plt.setp(texts, fontsize=14)
    
    ax.axis('equal')
    plt.savefig('pie_chart.png', bbox_inches='tight', facecolor='#FCF5E9')
    plt.close()

if __name__ == "__main__":
    if len(sys.argv) < 4:
        print("Error: Expected 3 arguments (income, expenses, balance).")
        sys.exit(1)

    try:
        income, expenses, balance = map(float, sys.argv[1:])
        create_pie_chart(income, expenses, balance)
    except ValueError as e:
        print(f"Error: {e}")
        sys.exit(1)
